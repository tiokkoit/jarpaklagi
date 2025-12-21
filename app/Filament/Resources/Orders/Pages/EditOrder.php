<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\SalesReport;
use App\Services\StockMovementService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('validate_and_send')
                ->label('Validate & Kirim')
                ->action(function () {
                    $order = $this->record;

                    if (in_array($order->status, ['CANCEL','SELESAI','DIKEMBALIKAN'])) {
                        Notification::make()->warning()->title('Status final tidak bisa diubah.')->send();
                        return;
                    }

                    // Hitung berapa pcs yang dibutuhkan (pcs_per_package * quantity)
                    $pkg = $order->productPackage;
                    if (! $pkg) {
                        Notification::make()->danger()->title('Paket produk tidak ditemukan.')->send();
                        return;
                    }

                    $pcsNeeded = ($pkg->pcs_per_package ?? 0) * (int)$order->quantity;
                    $productId = $pkg->product_id;

                    try {
                        // coba keluarkan stok
                        StockMovementService::stockOut($productId, 'order', $pcsNeeded, [
                            'reference_type' => Order::class,
                            'reference_id' => $order->id,
                        ]);

                        // jika berhasil, ubah status ke DIKIRIM
                        $order->update(['status' => 'DIKIRIM']);
                        Notification::make()->success()->title('Order berhasil dikirim, stok dikurangi.')->send();
                    } catch (\Exception $e) {
                        // jika gagal, cancel dan simpan ke sales report
                        $order->update(['status' => 'CANCEL']);

                        SalesReport::create([
                            'report_date' => now()->toDateString(),
                            'customer_name' => $order->customer_name,
                            'customer_address' => $order->customer_address,
                            'phone' => $order->phone,
                            'kecamatan' => $order->kecamatan,
                            'kota' => $order->kota,
                            'province' => $order->province,
                            'product_package_id' => $order->product_package_id,
                            'quantity' => $order->quantity,
                            'price' => $order->price,
                            'total_price' => $order->total_price,
                            'status' => 'CANCEL',
                            'payment' => $order->payment,
                        ]);

                        Notification::make()->danger()->title('Stok tidak mencukupi. Order dibatalkan dan dicatat ke Sales Report.')->send();
                    }
                }),

            Action::make('return_order')
                ->label('Kembalikan (Return)')
                ->requiresConfirmation()
                ->action(function () {
                    $order = $this->record;
                    if ($order->status !== 'DIKIRIM') {
                        Notification::make()->warning()->title('Hanya order yang berstatus DIKIRIM bisa dikembalikan.')->send();
                        return;
                    }

                    $pkg = $order->productPackage;
                    $pcs = ($pkg->pcs_per_package ?? 0) * (int)$order->quantity;

                    StockMovementService::stockIn($pkg->product_id, 'return_from_order', $pcs, [
                        'reference_type' => Order::class,
                        'reference_id' => $order->id,
                    ]);

                    $order->update(['status' => 'DIKEMBALIKAN']);

                    // store to sales report
                    SalesReport::create([
                        'report_date' => now()->toDateString(),
                        'customer_name' => $order->customer_name,
                        'customer_address' => $order->customer_address,
                        'phone' => $order->phone,
                        'kecamatan' => $order->kecamatan,
                        'kota' => $order->kota,
                        'province' => $order->province,
                        'product_package_id' => $order->product_package_id,
                        'quantity' => $order->quantity,
                        'price' => $order->price,
                        'total_price' => $order->total_price,
                        'status' => 'DIKEMBALIKAN',
                        'payment' => $order->payment,
                    ]);

                    Notification::make()->success()->title('Order dikembalikan dan stok direstorasi.')->send();
                }),

            Action::make('complete')
                ->label('Selesai')
                ->requiresConfirmation()
                ->action(function () {
                    $order = $this->record;
                    if ($order->status !== 'DIKIRIM') {
                        Notification::make()->warning()->title('Hanya order yang berstatus DIKIRIM bisa diselesaikan.')->send();
                        return;
                    }

                    $order->update(['status' => 'SELESAI']);

                    SalesReport::create([
                        'report_date' => now()->toDateString(),
                        'customer_name' => $order->customer_name,
                        'customer_address' => $order->customer_address,
                        'phone' => $order->phone,
                        'kecamatan' => $order->kecamatan,
                        'kota' => $order->kota,
                        'province' => $order->province,
                        'product_package_id' => $order->product_package_id,
                        'quantity' => $order->quantity,
                        'price' => $order->price,
                        'total_price' => $order->total_price,
                        'status' => 'SELESAI',
                        'payment' => $order->payment,
                    ]);

                    Notification::make()->success()->title('Order selesai dan dicatat ke Sales Report.')->send();
                }),
        ];
    }
}
