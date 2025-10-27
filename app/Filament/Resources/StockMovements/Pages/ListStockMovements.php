<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Models\StockMovement;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\StockMovements\StockMovementResource;


class ListStockMovements extends ListRecords
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create button (read-only)
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(StockMovement::count()),

            'in' => Tab::make('Stock In')
                ->query(fn ($query) => $query->where('type', 'in'))
                ->badge(StockMovement::where('type', 'in')->count())
                ->badgeColor('success'),

            'out' => Tab::make('Stock Out')
                ->query(fn ($query) => $query->where('type', 'out'))
                ->badge(StockMovement::where('type', 'out')->count())
                ->badgeColor('danger'),

            'today' => Tab::make('Today')
                ->query(fn ($query) => $query->whereDate('created_at', today()))
                ->badge(StockMovement::whereDate('created_at', today())->count())
                ->badgeColor('info'),
        ];
    }
}