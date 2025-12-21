<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\ProductPackage;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

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
                    $path = $data['file'];
                    $fullPath = storage_path('app/' . $path);
                    if (!file_exists($fullPath)) {
                        $this->notify('danger','File tidak ditemukan');
                        return;
                    }

                    if (($handle = fopen($fullPath, 'r')) !== false) {
                        $header = null;
                        $count = 0;
                        while (($row = fgetcsv($handle, 0, ',')) !== false) {
                            if (! $header) { $header = $row; continue; }
                            $dataRow = array_combine($header, $row);

                            // resolve product_package_id
                            $pkgId = null;
                            if (!empty($dataRow['product_package_id'])) {
                                $pkgId = (int)$dataRow['product_package_id'];
                            } elseif (!empty($dataRow['product_package_code'])) {
                                $pkg = ProductPackage::where('code', $dataRow['product_package_code'])->first();
                                $pkgId = $pkg?->id;
                            } elseif (!empty($dataRow['product_package_name'])) {
                                $pkg = ProductPackage::where('name', $dataRow['product_package_name'])->first();
                                $pkgId = $pkg?->id;
                            }

                            if (! $pkgId) { continue; }

                            $price = ProductPackage::find($pkgId)->price;
                            $quantity = (int)($dataRow['quantity'] ?? 1);

                            Order::create([
                                'order_date' => $dataRow['order_date'] ?? now()->toDateString(),
                                'customer_name' => $dataRow['customer_name'] ?? '-',
                                'customer_address' => $dataRow['customer_address'] ?? '-',
                                'phone' => $dataRow['phone'] ?? '-',
                                'kecamatan' => $dataRow['kecamatan'] ?? '-',
                                'kota' => $dataRow['kota'] ?? '-',
                                'province' => $dataRow['province'] ?? '-',
                                'product_package_id' => $pkgId,
                                'quantity' => $quantity,
                                'price' => $price,
                                'total_price' => $price * $quantity,
                                'status' => $dataRow['status'] ?? 'NEW',
                                'payment' => $dataRow['payment'] ?? 'COD',
                            ]);
                            $count++;
                        }
                        fclose($handle);
                        \Filament\Notifications\Notification::make()->success()->title("Import selesai: {$count} baris ditambahkan.")->send();
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
        return [
            'all' => Tab::make('All')
                ->badge(Order::count()),

            'new' => Tab::make('NEW')
                ->query(fn ($query) => $query->where('status', 'NEW'))
                ->badge(Order::where('status', 'NEW')->count())
                ->badgeColor('primary'),

            'dikirim' => Tab::make('DIKIRIM')
                ->query(fn ($query) => $query->where('status', 'DIKIRIM'))
                ->badge(Order::where('status', 'DIKIRIM')->count())
                ->badgeColor('info'),

            'cancel' => Tab::make('CANCEL')
                ->query(fn ($query) => $query->where('status', 'CANCEL'))
                ->badge(Order::where('status', 'CANCEL')->count())
                ->badgeColor('danger'),

            'selesai' => Tab::make('SELESAI')
                ->query(fn ($query) => $query->where('status', 'SELESAI'))
                ->badge(Order::where('status', 'SELESAI')->count())
                ->badgeColor('success'),

            'dikembalikan' => Tab::make('DIKEMBALIKAN')
                ->query(fn ($query) => $query->where('status', 'DIKEMBALIKAN'))
                ->badge(Order::where('status', 'DIKEMBALIKAN')->count())
                ->badgeColor('warning'),
        ];
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
