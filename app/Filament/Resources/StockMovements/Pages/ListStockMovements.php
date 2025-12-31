<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Models\StockMovement;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\StockMovements\StockMovementResource;
use App\Filament\Resources\StockMovements\Widgets\StockMovementStats;
use App\Filament\Resources\StockMovements\Widgets\StockMovementTrendChart;


class ListStockMovements extends ListRecords
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Tambah Stock Movement')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StockMovementStats::class,
            StockMovementTrendChart::class,
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(StockMovement::count()),

            'in' => Tab::make('Stock In')
                ->query(fn($query) => $query->where('type', 'in'))
                ->badge(StockMovement::where('type', 'in')->count())
                ->badgeColor('success'),

            'out' => Tab::make('Stock Out')
                ->query(fn($query) => $query->where('type', 'out'))
                ->badge(StockMovement::where('type', 'out')->count())
                ->badgeColor('danger'),

            'today' => Tab::make('Today')
                ->query(fn($query) => $query->whereDate('created_at', today()))
                ->badge(StockMovement::whereDate('created_at', today())->count())
                ->badgeColor('info'),
        ];
    }
}