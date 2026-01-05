<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Models\Product;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3, // Menggunakan sistem 3 kolom
            ])
            ->components([
                
                // --- KOLOM KIRI (UTAMA) ---
                Group::make([
                    Section::make('Detail Pergerakan Stok')
                        ->description('Pilih jenis pergerakan stok yang terjadi.')
                        ->icon('heroicon-m-arrows-right-left')
                        ->schema([
                            
                            // Baris 1: Produk (Full Width di dalam Section)
                            Select::make('product_id')
                                ->label('Pilih Produk')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpanFull()
                                ->hint('Produk yang akan diproses')
                                ->validationMessages([
                                    'required' => 'Pilih produk yang akan diproses pergerakan stoknya.',
                                ]),

                            // Baris 2: Tipe & Alasan (Berdampingan)
                            Grid::make(2)->schema([
                                Select::make('type')
                                    ->label('Tipe Pergerakan')
                                    ->options([
                                        'in' => '🟢 Stok Masuk (In)',
                                        'out' => '🔴 Stok Keluar (Out)',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->live() // Gunakan live() agar alasan langsung ter-update
                                    ->validationMessages([
                                        'required' => 'Pilih jenis pergerakan (Masuk/Keluar).',
                                    ]),

                                Select::make('reason')
                                    ->label('Alasan / Keterangan')
                                    ->options(function (Get $get) {
                                        $type = $get('type');
                                        $reasons = [
                                            'in' => [
                                                'restock' => 'Restock / Produksi',
                                                'adjustment_in' => 'Penyesuaian (Lebih)',
                                            ],
                                            'out' => [
                                                'damaged' => 'Barang Rusak',
                                                'expired' => 'Kadaluwarsa',
                                                'lost' => 'Hilang',
                                                'sample' => 'Pemberian Sample',
                                                'adjustment_out' => 'Penyesuaian (Kurang)',
                                            ],
                                        ];
                                        return $type ? $reasons[$type] : [];
                                    })
                                    ->required()
                                    ->native(false)
                                    ->disabled(fn (Get $get) => blank($get('type')))
                                    ->hintIcon('heroicon-m-question-mark-circle')
                                    ->validationMessages([
                                        'required' => 'Pilih alasan pergerakan stok.',
                                    ]),
                            ]),

                            // Baris 3: Quantity
                            TextInput::make('quantity')
                                ->label('Jumlah Pergerakan Stok')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->prefix('Qty')
                                ->suffix('Unit')
                                ->extraInputAttributes(['class' => 'text-xl font-bold']) // Agar angka terlihat jelas
                                ->placeholder('Masukan angka...')
                                ->validationMessages([
                                    'required' => 'Jumlah produk wajib diisi.',
                                    'minValue' => 'Minimal pergerakan adalah 1 unit.',
                                ]),
                        ]),
                ])->columnSpan(['lg' => 2]), // Sisi kiri mengambil 2/3 layar

                // --- KOLOM KANAN (SIDEBAR) ---
                Group::make([
                    Section::make('Catatan Tambahan')
                        ->icon('heroicon-m-clipboard-document-list')
                        ->schema([
                            Textarea::make('notes')
                                ->label('Keterangan')
                                ->placeholder('Contoh: Batch produksi #202, atau nomor nota retur...')
                                ->rows(5)
                                ->columnSpanFull(),
                        ]),

                    Section::make('Informasi')
                        ->schema([
                            Placeholder::make('info')
                                ->hiddenLabel()
                                ->content('Pastikan data yang diinput sudah sesuai dengan jumlah fisik produk di gudang CV Agrosehat.'),
                        ])
                        ->compact(),
                ])->columnSpan(['lg' => 1]), // Sisi kanan mengambil 1/3 layar
            ]);
    }
}
