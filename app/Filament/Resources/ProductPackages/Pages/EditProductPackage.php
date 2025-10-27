<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use App\Filament\Resources\ProductPackages\ProductPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductPackage extends EditRecord
{
    protected static string $resource = ProductPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Split name dan code saat edit
        if (isset($data['name']) && isset($data['product_id'])) {
            $product = \App\Models\Product::find($data['product_id']);
            if ($product) {
                // Buang prefix dari name
                $data['name_suffix'] = str_replace($product->name . ' ', '', $data['name']);
                
                // Buang prefix dari code
                $data['code_suffix'] = str_replace($product->code . '-', '', $data['code']);
            }
        }
        
        // PENTING: Tambahin ID untuk validasi
        $data['id'] = $data['id'] ?? $this->record->id;
        
        return $data;
    }
}