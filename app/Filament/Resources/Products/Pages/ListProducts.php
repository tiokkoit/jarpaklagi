<?php

namespace App\Filament\Resources\Products\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Widgets\AbcParetoChart;
use App\Filament\Resources\Products\Widgets\StockLevelChart;
use App\Filament\Resources\Products\Widgets\StockHealthChart;
use App\Filament\Resources\Products\Widgets\ProductStockChart;
use App\Filament\Resources\Products\Widgets\ProductStatsOverview;

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
            AbcParetoChart::class,
            StockHealthChart::class,
            StockLevelChart::class,
        ];
    }
}
