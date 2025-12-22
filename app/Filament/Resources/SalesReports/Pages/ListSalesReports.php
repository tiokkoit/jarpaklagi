<?php

namespace App\Filament\Resources\SalesReports\Pages;

use App\Filament\Resources\SalesReports\SalesReportResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductPackage;
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
                    while (($row = fgetcsv($handle, 0, ',')) !== false) {
                        if (! $header) {
                            $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
                            $header = array_map(fn($h) => strtolower(trim($h)), $row);
                            continue;
                        }
                        $dataRow = array_combine($header, $row);
                        // require report_date, quantity, status, and a product package identifier
                        $hasPkg = !empty($dataRow['product_package_id']) || !empty($dataRow['product_package_code']) || !empty($dataRow['product_package_name']);
                        if (empty($dataRow['report_date']) || empty($dataRow['quantity']) || empty($dataRow['status']) || ! $hasPkg) {
                            $skipped++;
                            continue;
                        }

                        // resolve product_package_id from id|code|name
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

                        if (! $pkgId) { $skipped++; continue; }

                        $pkg = ProductPackage::find($pkgId);
                        if (! $pkg) { $skipped++; continue; }

                        $rawStatus = strtolower(trim($dataRow['status'] ?? ''));
                        $statusMap = [
                            'cancel' => 'CANCEL',
                            'selesai' => 'SELESAI',
                            'dikembalikan' => 'DIKEMBALIKAN',
                        ];
                        if (! array_key_exists($rawStatus, $statusMap)) { $skipped++; continue; }
                        $status = $statusMap[$rawStatus];

                        $price = $pkg->price;
                        $quantity = (int)$dataRow['quantity'];
                        $total = $price * $quantity;

                        SalesReport::create([
                            'report_date' => $dataRow['report_date'],
                            'customer_name' => $dataRow['customer_name'] ?? null,
                            'customer_address' => $dataRow['customer_address'] ?? null,
                            'phone' => $dataRow['phone'] ?? null,
                            'kecamatan' => $dataRow['kecamatan'] ?? null,
                            'kota' => $dataRow['kota'] ?? null,
                            'province' => $dataRow['province'] ?? null,
                            'product_package_id' => $pkgId,
                            'quantity' => $quantity,
                            'price' => $price,
                            'total_price' => $total,
                            'status' => $status,
                            'payment' => $dataRow['payment'] ?? null,
                        ]);
                        $count++;
                    }
                    fclose($handle);
                    \Filament\Notifications\Notification::make()->success()->title("Import selesai: {$count} baris ditambahkan, {$skipped} baris dilewati.")->send();
                }
            });

        return [$import];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(SalesReport::count())
                ->badgeColor('secondary'),

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

