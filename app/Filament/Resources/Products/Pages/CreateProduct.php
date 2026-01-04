<?php

namespace App\Filament\Resources\Products\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Products\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-plus-circle') // Icon tambah
            ->title('Produk Berhasil Didaftarkan')
            // Kita bisa ambil nama produk yang baru dibuat secara dinamis
            ->body("**{$this->record->name}** kini telah tersimpan ke sistem StockkuApp.")
            ->send();
    }
}
