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

    /**
     * SATPAM UTAMA: Menjamin integritas data. 
     * Jika status Final, lempar ke View.
     */
    public function mount(int | string $record): void
    {
        parent::mount($record);

        if (in_array($this->record->status, ['CANCEL', 'SELESAI', 'DIKEMBALIKAN'])) {
            $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            // 🟢 VALIDATE: Muncul hanya jika status masih NEW
            Action::make('validate_and_send')
                ->label('Validate & Kirim')
                ->color('success')
                ->icon('heroicon-m-paper-airplane')
                ->visible(fn () => $this->record->status === 'NEW')
                ->action(function () {
                    $order = $this->record;
                    $pkg = $order->productPackage;
                    
                    if (!$pkg) {
                        Notification::make()->danger()->title('Paket tidak ditemukan.')->send();
                        return;
                    }

                    try {
                        StockMovementService::stockOut($pkg->product_id, 'order', ($pkg->pcs_per_package * $order->quantity), [
                            'reference_type' => Order::class,
                            'reference_id' => $order->id,
                        ]);

                        $order->update(['status' => 'DIKIRIM']);
                        Notification::make()->success()->title('Pesanan Berhasil Dikirim')->send();
                        $this->redirect($this->getResource()::getUrl('index'));
                    } catch (\Exception $e) {
                        $order->update(['status' => 'CANCEL']);
                        $this->createSalesReport($order, 'CANCEL');
                        Notification::make()->danger()->title('Stok Tidak Cukup')->body('Pesanan otomatis dibatalkan.')->send();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }
                }),

            // 🔴 CANCEL: Muncul hanya jika status masih NEW
            Action::make('cancel_manual')
                ->label('Batalkan')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'NEW')
                ->action(function () {
                    $this->record->update(['status' => 'CANCEL']);
                    $this->createSalesReport($this->record, 'CANCEL');
                    Notification::make()->danger()->title('Pesanan Dibatalkan')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // 🟡 RETURN: Muncul hanya jika status sudah DIKIRIM
            Action::make('return_order')
                ->label('Kembalikan (Return)')
                ->color('warning')
                ->icon('heroicon-m-arrow-uturn-left')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'DIKIRIM')
                ->action(function () {
                    $order = $this->record;
                    $pkg = $order->productPackage;
                    
                    StockMovementService::stockIn($pkg->product_id, 'return_from_order', ($pkg->pcs_per_package * $order->quantity), [
                        'reference_type' => Order::class,
                        'reference_id' => $order->id,
                    ]);

                    $order->update(['status' => 'DIKEMBALIKAN']);
                    $this->createSalesReport($order, 'DIKEMBALIKAN');
                    Notification::make()->warning()->title('Pesanan Dikembalikan, Stok Direstorasi')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // 🔵 SELESAI: Muncul hanya jika status sudah DIKIRIM
            Action::make('complete')
                ->label('Selesai')
                ->color('primary')
                ->icon('heroicon-m-check-badge')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'DIKIRIM')
                ->action(function () {
                    $this->record->update(['status' => 'SELESAI']);
                    $this->createSalesReport($this->record, 'SELESAI');
                    Notification::make()->success()->title('Transaksi Pesanan Selesai')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

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
}