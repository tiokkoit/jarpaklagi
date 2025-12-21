<?php

namespace App\Filament\Resources\SalesReports\Pages;

use App\Filament\Resources\SalesReports\SalesReportResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use App\Models\SalesReport;
use Illuminate\Database\Eloquent\Builder;

class ListSalesReports extends ListRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        $import = Action::make('import_csv')
            ->label('Import CSV')
            ->form([
                FileUpload::make('file')->label('CSV File')->required()->disk('local'),
            ])
            ->action(function (array $data) {
                $path = $data['file'];
                $fullPath = storage_path('app/' . $path);
                if (!file_exists($fullPath)) {
                    \Filament\Notifications\Notification::make()->danger()->title('File tidak ditemukan')->send();
                    return;
                }

                if (($handle = fopen($fullPath, 'r')) !== false) {
                    $header = null;
                    $count = 0;
                    while (($row = fgetcsv($handle, 0, ',')) !== false) {
                        if (! $header) { $header = $row; continue; }
                        $dataRow = array_combine($header, $row);

                        SalesReport::create([
                            'report_date' => $dataRow['report_date'] ?? now()->toDateString(),
                            'customer_name' => $dataRow['customer_name'] ?? '-',
                            'customer_address' => $dataRow['customer_address'] ?? '-',
                            'phone' => $dataRow['phone'] ?? '-',
                            'kecamatan' => $dataRow['kecamatan'] ?? '-',
                            'kota' => $dataRow['kota'] ?? '-',
                            'province' => $dataRow['province'] ?? '-',
                            'product_package_id' => $dataRow['product_package_id'] ?? null,
                            'quantity' => (int)($dataRow['quantity'] ?? 0),
                            'price' => (float)($dataRow['price'] ?? 0),
                            'total_price' => (float)($dataRow['total_price'] ?? 0),
                            'status' => $dataRow['status'] ?? 'CANCEL',
                            'payment' => $dataRow['payment'] ?? 'COD',
                        ]);
                        $count++;
                    }
                    fclose($handle);
                    \Filament\Notifications\Notification::make()->success()->title("Import selesai: {$count} baris ditambahkan.")->send();
                }
            });

        return [$import];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(SalesReport::count()),

            'cancel' => Tab::make('CANCEL')
                ->query(fn ($query) => $query->where('status', 'CANCEL'))
                ->badge(SalesReport::where('status', 'CANCEL')->count())
                ->badgeColor('danger'),

            'selesai' => Tab::make('SELESAI')
                ->query(fn ($query) => $query->where('status', 'SELESAI'))
                ->badge(SalesReport::where('status', 'SELESAI')->count())
                ->badgeColor('success'),

            'dikembalikan' => Tab::make('DIKEMBALIKAN')
                ->query(fn ($query) => $query->where('status', 'DIKEMBALIKAN'))
                ->badge(SalesReport::where('status', 'DIKEMBALIKAN')->count())
                ->badgeColor('warning'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = SalesReport::query();
        $status = request()->query('status');
        if ($status && strtoupper($status) !== 'ALL') {
            $query->where('status', strtoupper($status));
        }
        return $query;
    }
}

