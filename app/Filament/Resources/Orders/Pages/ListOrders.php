<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductPackage;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        $importAction = Action::make('import_csv')
            ->label('Import CSV')
            ->form([
                FileUpload::make('file')->label('CSV File')->required()->disk('local'),
            ])
            ->action(function (array $data) {
                    $path = $data['file'] ?? null;
                    if (empty($path) || ! is_string($path)) {
                        \Filament\Notifications\Notification::make()->danger()->title('File tidak ditemukan')->send();
                        return;
                    }

                    $fullPath = Storage::disk('local')->path($path);
                    if (!file_exists($fullPath)) {
                        \Filament\Notifications\Notification::make()->danger()->title('File tidak ditemukan')->send();
                        return;
                    }

                    if (($handle = fopen($fullPath, 'r')) !== false) {
                        $header = null;
                        $count = 0;
                        $skipped = 0;
                        $skipDetails = [];
                        $rowIndex = 0;
                        while (($row = fgetcsv($handle, 0, ',')) !== false) {
                            $rowIndex++;
                            if (! $header) {
                                $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
                                $header = array_map(fn($h) => strtolower(trim($h)), $row);
                                continue;
                            }
                            $dataRow = array_combine($header, $row);

                            // basic column validation (no defaults)
                            // require order_date, quantity, status, and a product package identifier
                            $missing = [];
                            if (empty($dataRow['order_date'])) { $missing[] = 'order_date'; }
                            if (empty($dataRow['quantity'])) { $missing[] = 'quantity'; }
                            if (empty($dataRow['status'])) { $missing[] = 'status'; }
                            $hasPkg = !empty($dataRow['product_package_id']) || !empty($dataRow['product_package_code']) || !empty($dataRow['product_package_name']);
                            if (! $hasPkg) { $missing[] = 'product_package_id|product_package_code|product_package_name'; }
                            if (! empty($missing)) {
                                $skipped++;
                                $skipDetails[] = "Line {$rowIndex}: missing required columns: " . implode(', ', $missing);
                                continue;
                            }

                            // resolve product_package_id
                            $pkgId = null;
                            $pkgLookup = null;
                            if (!empty($dataRow['product_package_id'])) {
                                $pkgId = (int)$dataRow['product_package_id'];
                                $pkgLookup = "id={$pkgId}";
                            } elseif (!empty($dataRow['product_package_code'])) {
                                $pkg = ProductPackage::where('code', $dataRow['product_package_code'])->first();
                                $pkgId = $pkg?->id;
                                $pkgLookup = "code={$dataRow['product_package_code']}";
                            } elseif (!empty($dataRow['product_package_name'])) {
                                $pkg = ProductPackage::where('name', $dataRow['product_package_name'])->first();
                                $pkgId = $pkg?->id;
                                $pkgLookup = "name={$dataRow['product_package_name']}";
                            }

                            if (! $pkgId) {
                                $skipped++;
                                $skipDetails[] = "Line {$rowIndex}: product package not found ({$pkgLookup})";
                                continue;
                            }

                            $pkg = ProductPackage::find($pkgId);
                            if (! $pkg) {
                                $skipped++;
                                $skipDetails[] = "Line {$rowIndex}: product package id {$pkgId} not found";
                                continue;
                            }

                            $price = $pkg->price;
                            $quantity = (int)$dataRow['quantity'];

                            // only accept statuses 'new' and 'dikirim' for Orders
                            $rawStatus = strtolower(trim($dataRow['status'] ?? ''));
                            $statusMap = [
                                'new' => 'NEW',
                                'dikirim' => 'DIKIRIM',
                            ];
                            if (! array_key_exists($rawStatus, $statusMap)) {
                                $skipped++;
                                $skipDetails[] = "Line {$rowIndex}: invalid status '{$dataRow['status']}'";
                                continue;
                            }
                            $status = $statusMap[$rawStatus];

                            Order::create([
                                'order_date' => $dataRow['order_date'],
                                'customer_name' => $dataRow['customer_name'] ?? null,
                                'customer_address' => $dataRow['customer_address'] ?? null,
                                'phone' => $dataRow['phone'] ?? null,
                                'kecamatan' => $dataRow['kecamatan'] ?? null,
                                'kota' => $dataRow['kota'] ?? null,
                                'province' => $dataRow['province'] ?? null,
                                'product_package_id' => $pkgId,
                                'quantity' => $quantity,
                                'price' => $price,
                                'total_price' => $price * $quantity,
                                'status' => $status,
                                'payment' => $dataRow['payment'] ?? null,
                            ]);
                            $count++;
                        }
                        fclose($handle);

                        if ($skipped > 0) {
                            $timestamp = now()->format('Ymd_His');
                            $logPath = "import_logs/orders_import_{$timestamp}.log";
                            $content = "Import: Orders\nFile: {$path}\nInserted: {$count}\nSkipped: {$skipped}\n\nDetails:\n" . implode("\n", $skipDetails);
                            Storage::disk('local')->put($logPath, $content);
                            \Filament\Notifications\Notification::make()->success()->title("Import selesai: {$count} baris ditambahkan, {$skipped} baris dilewati.")->body("Log: {$logPath}")->send();
                        } else {
                            \Filament\Notifications\Notification::make()->success()->title("Import selesai: {$count} baris ditambahkan.")->send();
                        }
                    }
                });

        // ensure a Create button is present (some setups may hide default create)
        $createAction = Action::make('create')
            ->label('Create')
            ->url(OrderResource::getUrl('create'))
            ->icon('heroicon-o-plus')
            ->color('primary');

        // merge with parent actions and ensure create exists
        $actions = parent::getHeaderActions();

        $hasCreate = collect($actions)->contains(function ($a) {
            try { return method_exists($a, 'getName') && $a->getName() === 'create'; } catch (\Throwable $e) { return false; }
        });

        if (! $hasCreate) {
            array_unshift($actions, $createAction);
        }

        $actions[] = $importAction;

        return $actions;
    }

    public function getTabs(): array
    {
        // parse current table filters (Filament typically sends them under "tableFilters")
        $tableFilters = request()->query('tableFilters') ?? request()->query('table_filters') ?? [];
        if (is_string($tableFilters)) {
            $decoded = json_decode(urldecode($tableFilters), true);
            if (is_array($decoded)) {
                $tableFilters = $decoded;
            }
        }
        $timeData = $tableFilters['time'] ?? [];

        $baseQuery = Order::query();
        $this->applyTimeFilter($baseQuery, $timeData);

        $counts = [
            'new' => (clone $baseQuery)->where('status', 'NEW')->count(),
            'dikirim' => (clone $baseQuery)->where('status', 'DIKIRIM')->count(),
            'cancel' => (clone $baseQuery)->where('status', 'CANCEL')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'SELESAI')->count(),
            'dikembalikan' => (clone $baseQuery)->where('status', 'DIKEMBALIKAN')->count(),
            'all' => (clone $baseQuery)->count(),
        ];

        return [
            'new' => Tab::make('NEW')
                ->query(fn ($query) => $query->where('status', 'NEW'))
                ->badge($counts['new'])
                ->badgeColor('info'),

            'dikirim' => Tab::make('DIKIRIM')
                ->query(fn ($query) => $query->where('status', 'DIKIRIM'))
                ->badge($counts['dikirim'])
                ->badgeColor('gray'),

            'cancel' => Tab::make('CANCEL')
                ->query(fn ($query) => $query->where('status', 'CANCEL'))
                ->badge($counts['cancel'])
                ->badgeColor('danger'),

            'selesai' => Tab::make('SELESAI')
                ->query(fn ($query) => $query->where('status', 'SELESAI'))
                ->badge($counts['selesai'])
                ->badgeColor('success'),

            'dikembalikan' => Tab::make('DIKEMBALIKAN')
                ->query(fn ($query) => $query->where('status', 'DIKEMBALIKAN'))
                ->badge($counts['dikembalikan'])
                ->badgeColor('warning'),
            
            'all' => Tab::make('All')
                ->badge($counts['all'])
                ->badgeColor('secondary'),
        ];
    }

    protected function applyTimeFilter(Builder $query, array $data): Builder
    {
        $preset = $data['preset'] ?? null;
        if ($preset === 'today') {
            return $query->whereDate('order_date', Carbon::today()->toDateString());
        }

        if ($preset === 'last_3') {
            return $query->whereBetween('order_date', [Carbon::now()->subDays(2)->toDateString(), Carbon::now()->toDateString()]);
        }

        if ($preset === 'this_week') {
            return $query->whereBetween('order_date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()]);
        }

        if ($preset === 'last_week') {
            return $query->whereBetween('order_date', [Carbon::now()->subWeek()->startOfWeek()->toDateString(), Carbon::now()->subWeek()->endOfWeek()->toDateString()]);
        }

        if ($preset === 'month' && ! empty($data['month_year']) && ! empty($data['month_number'])) {
            $y = (int) $data['month_year'];
            $m = (int) $data['month_number'];
            return $query->whereYear('order_date', $y)->whereMonth('order_date', $m);
        }

        if ($preset === 'year' && ! empty($data['year_only'])) {
            $y = (int) $data['year_only'];
            return $query->whereYear('order_date', $y);
        }

        if ($preset === 'range' && ! empty($data['start']) && ! empty($data['end'])) {
            return $query->whereBetween('order_date', [Carbon::parse($data['start'])->toDateString(), Carbon::parse($data['end'])->toDateString()]);
        }

        return $query;
    }

    protected function getTableQuery(): Builder
    {
        $query = Order::query();
        $status = request()->query('status');
        if ($status && strtoupper($status) !== 'ALL') {
            $query->where('status', strtoupper($status));
        }
        return $query;
    }
}
