<?php

namespace App\Filament\Resources\StockMovements\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StockMovements\StockMovementResource;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-plus-circle') // Icon tambah
            ->title('Pergerakan Stok Berhasil Dicatat')
            // Kita bisa ambil nama produk yang baru dibuat secara dinamis
            ->body("Pergerakan stok untuk **{$this->record->product->name}** kini telah tercatat di sistem StockkuApp.")
            ->send();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['reference_type'] = null;
        $data['reference_id'] = null;

        // ✅ Ambil stok produk saat ini
        $product = \App\Models\Product::find($data['product_id']);
        $currentStock = $product->stock ?? 0;

        // ✅ Hitung stok sebelum dan sesudah
        $data['stock_before'] = $currentStock;
        $data['stock_after'] = $data['type'] === 'in'
            ? $currentStock + $data['quantity']
            : $currentStock - $data['quantity'];

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $product = $record->product;

        // ✅ Update stok produk sesuai pergerakan
        if ($record->type === 'in') {
            $product->increment('stock', $record->quantity);
        } else {
            $product->decrement('stock', $record->quantity);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
