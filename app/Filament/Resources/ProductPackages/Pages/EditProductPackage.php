<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductPackages\ProductPackageResource;

class EditProductPackage extends EditRecord
{
    protected static string $resource = ProductPackageResource::class;

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
                ->label('Hapus Paket')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Paket Produk')
                ->modalSubheading('Tindakan ini tidak dapat dibatalkan. Yakin ingin menghapus paket ini?')
                ->modalButton('Ya, Hapus')
                ->successNotification(
                    Notification::make()
                        ->danger() // Warna merah
                        ->icon('heroicon-o-trash')
                        ->title('Paket Telah Dihapus')
                        ->body('Data paket berhasil dibersihkan dari database StockkuApp.')
                ),
        ];
    }

    /**
     * Mempersiapkan data sebelum form ditampilkan (Edit Mode)
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['name'], $data['product_id'])) {
            $product = Product::find($data['product_id']);

            if ($product) {
                // Memisahkan prefix nama & kode agar user hanya melihat 'suffix'-nya saja di input
                // Contoh: 'Moringa Paket 1' menjadi 'Paket 1'
                $data['name_suffix'] = str_replace($product->name . ' ', '', $data['name']);
                
                // Contoh: 'MOE-PC01' menjadi 'PC01'
                $data['code_suffix'] = str_replace($product->code . '-', '', $data['code']);
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}