<?php

namespace App\Filament\Resources\StockMovements\Tables;


use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->badge()
                    ->color(fn ($state) => $state === 'in' ? 'success' : 'danger'),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->formatStateUsing(function ($state) {
                        $reasons = [
                            'initial_stock' => 'Initial Stock',
                            'restock' => 'Restock',
                            'return_from_order' => 'Return Order',
                            'return_from_damage' => 'Return Damage',
                            'adjustment_in' => 'Adjustment In',
                            'order' => 'Order Out',
                            'damaged' => 'Damaged',
                            'expired' => 'Expired',
                            'lost' => 'Lost',
                            'sample' => 'Sample',
                            'adjustment_out' => 'Adjustment Out',
                        ];
                        return $reasons[$state] ?? $state;
                    })
                    ->badge()
                    ->color('gray'),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->formatStateUsing(function ($record) {
                        $sign = $record->type === 'in' ? '+' : '-';
                        return $sign . number_format($record->quantity);
                    })
                    ->badge()
                    ->color(fn ($record) => $record->type === 'in' ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('stock_before')
                    ->label('Before')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->alignEnd()
                    ->sortable(),

                TextColumn::make('stock_after')
                    ->label('After')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->alignEnd()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->formatStateUsing(function ($record) {
                        if (!$record->reference) {
                            return 'Manual Entry';
                        }
                        
                        if ($record->reference instanceof \App\Models\Order) {
                            return 'Order #' . $record->reference->order_number;
                        }
                        
                        if ($record->reference instanceof \App\Models\Shipment) {
                            return 'Shipment #' . $record->reference->shipment_number;
                        }
                        
                        return 'Manual Entry';
                    })
                    ->badge()
                    ->color('info')
                    ->limit(20),

                TextColumn::make('createdBy.name')
                    ->label('By')
                    ->default('System')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->size('sm'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'in' => 'Stock In',
                        'out' => 'Stock Out',
                    ]),

                SelectFilter::make('reason')
                    ->label('Reason')
                    ->options([
                        'initial_stock' => 'Initial Stock',
                        'restock' => 'Restock',
                        'return_from_order' => 'Return from Order',
                        'adjustment_in' => 'Adjustment In',
                        'order' => 'Order',
                        'damaged' => 'Damaged',
                        'expired' => 'Expired',
                        'lost' => 'Lost',
                        'sample' => 'Sample',
                        'adjustment_out' => 'Adjustment Out',
                    ]),

                SelectFilter::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon('heroicon-o-eye'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('30s'); // Auto refresh every 30 seconds
    }
}