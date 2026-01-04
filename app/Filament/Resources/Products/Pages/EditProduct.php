<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus Produk')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Produk')
                ->modalSubheading('Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus produk ini?')
                ->modalButton('Ya, Hapus'),
        ];
    }
}
