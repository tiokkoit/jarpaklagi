<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Resources\Pages\EditRecord;

class EditStockMovement extends EditRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pastikan manual record aja yang bisa diubah
        if ($this->record->reference_type) {
            abort(403, 'Tidak dapat mengedit movement dari sistem.');
        }

        return $data;
    }
}
