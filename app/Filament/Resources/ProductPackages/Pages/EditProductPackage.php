<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use App\Filament\Resources\ProductPackages\ProductPackageResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductPackage extends EditRecord
{
    protected static string $resource = ProductPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Paket')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Paket Produk')
                ->modalSubheading('Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus paket ini?')
                ->modalButton('Ya, Hapus'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['name'], $data['product_id'])) {
            $product = Product::find($data['product_id']);

            if ($product) {
                // Hapus prefix nama & kode
                $data['name_suffix'] = str_replace($product->name . ' ', '', $data['name']);
                $data['code_suffix'] = str_replace($product->code . '-', '', $data['code']);
            }
        }

        // Tambahkan ID untuk validasi (agar update tidak dianggap duplikat)
        $data['id'] = $data['id'] ?? $this->record->id;

        return $data;
    }
}
