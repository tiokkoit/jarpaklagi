<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Movement Data')
                    ->description('Isi detail pergerakan stok dengan lengkap.')
                    ->icon('heroicon-o-arrow-path')
                    ->columns(2)
                    ->schema([
                        // === Select Product ===
                        Select::make('product_id')
                            ->label('Product')
                            ->options(fn () => Product::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->hint('Pilih produk yang akan diubah stoknya'),

                        // === Select Type (IN / OUT) ===
                        Select::make('type')
                            ->label('Movement Type')
                            ->options([
                                'in' => 'Stock In',
                                'out' => 'Stock Out',
                            ])
                            ->required()
                            ->native(false)
                            ->reactive()
                            ->hint('Pilih jenis pergerakan stok'),

                        // === Select Reason (Dynamic) ===
                        Select::make('reason')
                            ->label('Reason')
                            ->options(function (callable $get) {
                                $type = $get('type');

                                $reasons = [
                                    'in' => [
                                        'initial_stock' => 'Initial Stock',
                                        'restock' => 'Restock',
                                        'return_from_order' => 'Return from Order',
                                        'adjustment_in' => 'Adjustment In',
                                    ],
                                    'out' => [
                                        'order' => 'Order Out',
                                        'damaged' => 'Damaged',
                                        'expired' => 'Expired',
                                        'lost' => 'Lost',
                                        'sample' => 'Sample',
                                        'adjustment_out' => 'Adjustment Out',
                                    ],
                                ];

                                return $type ? $reasons[$type] : [];
                            })
                            ->required()
                            ->reactive()
                            ->disabled(fn (callable $get) => blank($get('type')))
                            ->native(false)
                            ->hint('Opsi alasan akan muncul setelah memilih tipe stok'),

                        // === Quantity Input ===
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('Qty')
                            ->hint('Jumlah barang yang bergerak'),

                        // === Notes ===
                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Opsional — isi jika ada catatan tambahan, misalnya alasan koreksi.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
