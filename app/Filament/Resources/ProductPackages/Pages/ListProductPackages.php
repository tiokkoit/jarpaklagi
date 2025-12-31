<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use App\Filament\Resources\ProductPackages\ProductPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageStats;

class ListProductPackages extends ListRecords
{
    protected static string $resource = ProductPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Paket Penjualan Produk Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductPackageStats::class,
            \App\Filament\Resources\ProductPackages\Widgets\ProductPackageActiveChart::class,
        ];
    }
}
