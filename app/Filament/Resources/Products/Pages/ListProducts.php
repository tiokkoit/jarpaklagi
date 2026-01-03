<?php

namespace App\Filament\Resources\Products\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Widgets\StockHealthChart;
use App\Filament\Resources\Products\Widgets\ProductValueChart;
use App\Filament\Resources\Products\Widgets\ProductHppChart;
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
            StockHealthChart::class,
            ProductValueChart::class,
            ProductHppChart::class,
        ];
    }
}
