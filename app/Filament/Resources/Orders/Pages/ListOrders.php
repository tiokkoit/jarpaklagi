<?php

namespace App\Filament\Resources\Orders\Pages;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Actions\Action;
use App\Models\ProductPackage;
// NAMESPACE YANG BENAR UNTUK FILAMENT v3/v4: 
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Widgets\OrderStatsOverview;
use App\Filament\Resources\Orders\Widgets\OrderStatusChart;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    /**
     * Header Actions: Create & Import CSV
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_csv')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->form([
                    FileUpload::make('file')
                        ->label('File CSV')
                        ->required()
                        ->disk('local')
                        ->directory('imports'),
                ])
                ->action(fn(array $data) => $this->runImport($data)),

            Action::make('create')
                ->label('Tambah Order')
                ->url(OrderResource::getUrl('create'))
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsOverview::class,
            OrderStatusChart::class,
        ];
    }

    /**
     * Logika TAB & Badge Reaktif
     */
    public function getTabs(): array
    {
        // Ambil data filter 'time' dari tabel secara real-time
        $timeData = $this->tableFilters['time'] ?? [];

        // Buat query dasar
        $baseQuery = Order::query();

        // Terapkan filter waktu ke query dasar (sinkron dengan filter tabel)
        $baseQuery = $this->applyTimeFilter($baseQuery, $timeData);

        return [
            'all' => Tab::make('ALL')
                ->badge((clone $baseQuery)->count())
                ->badgeColor('secondary'),

            'new' => Tab::make('NEW')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'NEW'))
                ->badge((clone $baseQuery)->where('status', 'NEW')->count())
                ->badgeColor('info')
                ->icon('heroicon-o-sparkles'),

            'dikirim' => Tab::make('DIKIRIM')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'DIKIRIM'))
                ->badge((clone $baseQuery)->where('status', 'DIKIRIM')->count())
                ->badgeColor('gray')
                ->icon('heroicon-o-truck'),

            'selesai' => Tab::make('SELESAI')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'SELESAI'))
                ->badge((clone $baseQuery)->where('status', 'SELESAI')->count())
                ->badgeColor('success')
                ->icon('heroicon-o-check-badge'),

            'cancel' => Tab::make('CANCEL')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'CANCEL'))
                ->badge((clone $baseQuery)->where('status', 'CANCEL')->count())
                ->badgeColor('danger')
                ->icon('heroicon-o-no-symbol'),

            'dikembalikan' => Tab::make('DIKEMBALIKAN')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'DIKEMBALIKAN'))
                ->badge((clone $baseQuery)->where('status', 'DIKEMBALIKAN')->count())
                ->badgeColor('warning')
                ->icon('heroicon-o-backspace'),
        ];
    }

    /**
     * Sinkronisasi logika filter waktu
     */
    protected function applyTimeFilter(Builder $query, array $data): Builder
    {
        $preset = $data['preset'] ?? null;

        return match ($preset) {
            'today' => $query->whereDate('order_date', Carbon::today()->toDateString()),
            'last_3' => $query->whereBetween('order_date', [Carbon::now()->subDays(2)->toDateString(), Carbon::now()->toDateString()]),
            'this_week' => $query->whereBetween('order_date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()]),
            'last_week' => $query->whereBetween('order_date', [Carbon::now()->subWeek()->startOfWeek()->toDateString(), Carbon::now()->subWeek()->endOfWeek()->toDateString()]),
            'month' => (isset($data['month_year'], $data['month_number']))
            ? $query->whereYear('order_date', (int) $data['month_year'])->whereMonth('order_date', (int) $data['month_number'])
            : $query,
            'year' => (!empty($data['year_only'])) ? $query->whereYear('order_date', (int) $data['year_only']) : $query,
            'range' => (isset($data['start'], $data['end']))
            ? $query->whereBetween('order_date', [Carbon::parse($data['start'])->toDateString(), Carbon::parse($data['end'])->toDateString()])
            : $query,
            default => $query,
        };
    }

    /**
     * Logika Import CSV
     */
    protected function runImport(array $data): void
    {
        $path = $data['file'] ?? null;
        if (empty($path))
            return;

        $fullPath = Storage::disk('local')->path($path);
        if (!file_exists($fullPath)) {
            Notification::make()->danger()->title('File tidak ditemukan')->send();
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
                if (!$header) {
                    $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
                    $header = array_map(fn($h) => strtolower(trim($h)), $row);
                    continue;
                }

                $dataRow = array_combine($header, $row);

                if (empty($dataRow['order_date']) || empty($dataRow['quantity']) || empty($dataRow['status'])) {
                    $skipped++;
                    $skipDetails[] = "Line {$rowIndex}: Missing required data.";
                    continue;
                }

                $pkg = ProductPackage::where('id', $dataRow['product_package_id'] ?? null)
                    ->orWhere('code', $dataRow['product_package_code'] ?? null)
                    ->first();

                if (!$pkg) {
                    $skipped++;
                    $skipDetails[] = "Line {$rowIndex}: Package not found.";
                    continue;
                }

                $statusMap = [
                    'new' => 'NEW',
                    'dikirim' => 'DIKIRIM',
                    'selesai' => 'SELESAI',
                    'cancel' => 'CANCEL',
                    'dikembalikan' => 'DIKEMBALIKAN'
                ];
                $status = $statusMap[strtolower(trim($dataRow['status']))] ?? null;

                if (!$status) {
                    $skipped++;
                    $skipDetails[] = "Line {$rowIndex}: Invalid status.";
                    continue;
                }

                Order::create([
                    'order_date' => $dataRow['order_date'],
                    'customer_name' => $dataRow['customer_name'] ?? null,
                    'customer_address' => $dataRow['customer_address'] ?? null,
                    'phone' => $dataRow['phone'] ?? null,
                    'kecamatan' => $dataRow['kecamatan'] ?? null,
                    'kota' => $dataRow['kota'] ?? null,
                    'province' => $dataRow['province'] ?? null,
                    'product_package_id' => $pkg->id,
                    'quantity' => (int) $dataRow['quantity'],
                    'price' => $pkg->price,
                    'total_price' => $pkg->price * (int) $dataRow['quantity'],
                    'status' => $status,
                    'payment' => $dataRow['payment'] ?? null,
                ]);
                $count++;
            }
            fclose($handle);

            if ($skipped > 0) {
                $logFile = 'import_logs/orders_' . now()->format('Ymd_His') . '.log';
                Storage::disk('local')->put($logFile, implode("\n", $skipDetails));
                Notification::make()->warning()->title("Import selesai dengan {$skipped} error.")->body("Log: storage/app/{$logFile}")->send();
            } else {
                Notification::make()->success()->title("Berhasil mengimpor {$count} data.")->send();
            }
        }
    }
}