<?php

namespace App\Filament\Resources\StockMovements\Schemas;


use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // SECTION 1: Product Info
                Section::make('Product Information')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('product.name')
                            ->label('Product Name')
                            ->content(fn ($record) => $record?->product?->name ?? '-'),

                        Placeholder::make('product.code')
                            ->label('Product Code')
                            ->content(fn ($record) => $record?->product?->code ?? '-'),
                    ]),

                // SECTION 2: Movement Details
                Section::make('Movement Details')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('type')
                            ->label('Type')
                            ->content(function ($record) {
                                if (!$record) return '-';
                                return strtoupper($record->type);
                            }),

                        Placeholder::make('reason')
                            ->label('Reason')
                            ->content(fn ($record) => $record?->reason_text ?? '-'),

                        Placeholder::make('quantity')
                            ->label('Quantity')
                            ->content(function ($record) {
                                if (!$record) return '-';
                                $sign = $record->type === 'in' ? '+' : '-';
                                return $sign . number_format($record->quantity) . ' pcs';
                            }),
                    ]),

                // SECTION 3: Stock Changes
                Section::make('Stock Changes')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('stock_before')
                            ->label('Stock Before')
                            ->content(fn ($record) => number_format($record?->stock_before ?? 0) . ' pcs'),

                        Placeholder::make('arrow')
                            ->label(' ')
                            ->content('→'),

                        Placeholder::make('stock_after')
                            ->label('Stock After')
                            ->content(fn ($record) => number_format($record?->stock_after ?? 0) . ' pcs'),
                    ]),

                // SECTION 4: Reference & Notes
                Section::make('Additional Information')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('reference')
                            ->label('Reference')
                            ->content(function ($record) {
                                if (!$record || !$record->reference) {
                                    return 'Manual Entry';
                                }
                                
                                if ($record->reference instanceof \App\Models\Order) {
                                    return 'Order #' . $record->reference->order_number;
                                }
                                
                                if ($record->reference instanceof \App\Models\Shipment) {
                                    return 'Shipment #' . $record->reference->shipment_number;
                                }
                                
                                return 'Manual Entry';
                            }),

                        Placeholder::make('createdBy.name')
                            ->label('Created By')
                            ->content(fn ($record) => $record?->createdBy?->name ?? 'System'),

                        Placeholder::make('notes')
                            ->label('Notes')
                            ->content(fn ($record) => $record?->notes ?? '-')
                            ->columnSpanFull(),

                        Placeholder::make('created_at')
                            ->label('Created At')
                            ->content(fn ($record) => $record?->created_at?->format('d F Y, H:i:s') ?? '-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}