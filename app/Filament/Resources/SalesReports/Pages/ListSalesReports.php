<?php

namespace App\Filament\Resources\SalesReports\Pages;

use App\Filament\Resources\SalesReports\SalesReportResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use App\Models\SalesReport;


class ListSalesReports extends ListRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_csv')
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
                        $this->notify('success', "Import selesai: {$count} baris ditambahkan.");
                    }
                }),
        ];
    }
}
