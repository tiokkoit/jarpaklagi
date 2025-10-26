<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('type')
                    ->options(['in' => 'In', 'out' => 'Out'])
                    ->required(),
                Select::make('reason')
                    ->options([
            'initial_stock' => 'Initial stock',
            'restock' => 'Restock',
            'return_from_order' => 'Return from order',
            'adjustment_in' => 'Adjustment in',
            'order' => 'Order',
            'damaged' => 'Damaged',
            'expired' => 'Expired',
            'lost' => 'Lost',
            'sample' => 'Sample',
            'adjustment_out' => 'Adjustment out',
        ])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_before')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_after')
                    ->required()
                    ->numeric(),
                TextInput::make('reference_type'),
                TextInput::make('reference_id')
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
