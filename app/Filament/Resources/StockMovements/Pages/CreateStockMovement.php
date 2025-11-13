<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;

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
