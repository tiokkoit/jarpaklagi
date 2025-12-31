<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Products\Widgets\ProductStatsOverview;
use App\Filament\Resources\Products\Widgets\ProductStockChart;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Produk Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStatsOverview::class,
            ProductStockChart::class,
            \App\Filament\Resources\Products\Widgets\ProductValueChart::class,
        ];
    }
}
