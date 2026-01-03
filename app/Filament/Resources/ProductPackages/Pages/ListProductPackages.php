<?php

namespace App\Filament\Resources\ProductPackages\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductPackages\ProductPackageResource;
use App\Filament\Resources\ProductPackages\Widgets\PackageMarginChart;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageStats;
use App\Filament\Resources\ProductPackages\Widgets\ProductPriceRangeChart;
use App\Filament\Resources\ProductPackages\Widgets\PackageProfitNominalChart;

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
            PackageMarginChart::class,
            PackageProfitNominalChart::class,
            ProductPriceRangeChart::class,
        ];
    }
}
