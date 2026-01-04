<?php

namespace App\Filament\Resources\Products\Pages;


use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Products\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->icon('heroicon-o-arrow-path') // Icon update/sinkron
            ->iconColor('info')
            ->title('Pembaruan Berhasil')
            ->body("Data **{$this->record->name}** telah diperbarui sesuai input terbaru.")
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Produk')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Produk')
                ->modalSubheading('Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus produk ini?')
                ->modalButton('Ya, Hapus')
                ->successNotification(
                    Notification::make()
                        ->danger() // Warna merah
                        ->icon('heroicon-o-trash')
                        ->title('Produk Telah Dihapus')
                        ->body('Data produk berhasil dibersihkan dari database StockkuApp.')
                ),
        ];
    }
}
