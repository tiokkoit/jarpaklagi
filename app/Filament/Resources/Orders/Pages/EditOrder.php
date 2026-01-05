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
            // 🟢 TOMBOL VALIDATE & KIRIM
            // Hanya muncul jika status BELUM dikirim/selesai/batal (Status Awal)
            Action::make('validate_and_send')
                ->label('Validate & Kirim')
                ->color('success')
                ->icon('heroicon-m-paper-airplane')
                ->visible(fn () => !in_array($this->record->status, ['DIKIRIM', 'CANCEL', 'SELESAI', 'DIKEMBALIKAN']))
                ->action(function () {
                    $order = $this->record;

                    $pkg = $order->productPackage;
                    if (!$pkg) {
                        Notification::make()->danger()->title('Paket produk tidak ditemukan.')->send();
                        return;
                    }

                    $pcsNeeded = ($pkg->pcs_per_package ?? 0) * (int)$order->quantity;
                    $productId = $pkg->product_id;

                    try {
                        StockMovementService::stockOut($productId, 'order', $pcsNeeded, [
                            'reference_type' => Order::class,
                            'reference_id' => $order->id,
                        ]);

                        $order->update(['status' => 'DIKIRIM']);
                        Notification::make()->success()->title('Order dikirim, stok berkurang.')->send();
                        $this->redirect($this->getResource()::getUrl('index'));
                    } catch (\Exception $e) {
                        $order->update(['status' => 'CANCEL']);
                        $this->createSalesReport($order, 'CANCEL');
                        Notification::make()->danger()->title('Stok tidak mencukupi. Order otomatis dibatalkan.')->send();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }
                }),

            // 🔴 TOMBOL CANCEL (MANUAL)
            // Hanya muncul di tahap awal sebelum barang dikirim
            Action::make('cancel_manual')
                ->label('Batalkan Pesanan')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->requiresConfirmation()
                ->visible(fn () => !in_array($this->record->status, ['DIKIRIM', 'CANCEL', 'SELESAI', 'DIKEMBALIKAN']))
                ->action(function () {
                    $order = $this->record;
                    $order->update(['status' => 'CANCEL']);
                    $this->createSalesReport($order, 'CANCEL');
                    
                    Notification::make()->warning()->title('Pesanan telah dibatalkan.')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // 🟡 TOMBOL RETURN (KEMBALIKAN)
            // Hanya muncul jika status sudah DIKIRIM
            Action::make('return_order')
                ->label('Kembalikan (Return)')
                ->color('warning')
                ->icon('heroicon-m-arrow-uturn-left')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'DIKIRIM')
                ->action(function () {
                    $order = $this->record;
                    $pkg = $order->productPackage;
                    $pcs = ($pkg->pcs_per_package ?? 0) * (int)$order->quantity;

                    StockMovementService::stockIn($pkg->product_id, 'return_from_order', $pcs, [
                        'reference_type' => Order::class,
                        'reference_id' => $order->id,
                    ]);

                    $order->update(['status' => 'DIKEMBALIKAN']);
                    $this->createSalesReport($order, 'DIKEMBALIKAN');

                    Notification::make()->success()->title('Stok berhasil direstorasi.')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // 🔵 TOMBOL SELESAI
            // Hanya muncul jika status sudah DIKIRIM
            Action::make('complete')
                ->label('Selesai')
                ->color('primary')
                ->icon('heroicon-m-check-badge')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'DIKIRIM')
                ->action(function () {
                    $order = $this->record;
                    $order->update(['status' => 'SELESAI']);
                    $this->createSalesReport($order, 'SELESAI');

                    Notification::make()->success()->title('Order SELESAI. Tercatat di Sales Report.')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

    /**
     * Helper function untuk mencatat ke Sales Report agar kode tidak duplikat (Clean Code)
     */
    protected function createSalesReport(Order $order, string $status): void
    {
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
            'status' => $status,
            'payment' => $order->payment,
        ]);
    }

    // Di EditOrder.php
    protected function getFormActions(): array
    {
        $order = $this->record;

        // Jika status sudah final, jangan tampilkan tombol aksi form (Simpan/Batal)
        if (in_array($order->status, ['CANCEL', 'SELESAI', 'DIKEMBALIKAN'])) {
            return [];
        }

        return parent::getFormActions();
    }
}