<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductPackages\ProductPackageResource;

class CreateProductPackage extends CreateRecord
{
    protected static string $resource = ProductPackageResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-plus-circle') // Icon tambah
            ->title('Paket Berhasil Didaftarkan')
            // Kita bisa ambil nama paket yang baru dibuat secara dinamis
            ->body("**{$this->record->name}** kini telah tersimpan ke sistem StockkuApp.")
            ->send();
    }
}
