<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION 1: INFO BARANG
                Section::make('Identitas Produk')
                    ->description('Atur kode SKU dan nama produk.')
                    ->icon('heroicon-m-identification')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('code')
                                ->label('Kode SKU')
                                ->placeholder('Contoh: MOE01')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(50)
                                ->autofocus()
                                ->helperText('Kode unik untuk tracking produk.'),
                            
                            TextInput::make('name')
                                ->label('Nama Produk')
                                ->placeholder('Contoh: Moera Infusion')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),
                        ]),
                    ]),

                // SECTION 2: MODAL & STOK
                Section::make('Modal & Stok Awal')
                    ->description('Isi harga modal (HPP) dan jumlah produk yang masuk pertama kali ke sistem.')
                    ->icon('heroicon-m-banknotes')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('hpp')
                                ->label('Harga Modal (HPP)')
                                ->numeric()
                                ->prefix('Rp')
                                ->placeholder('0')
                                ->extraInputAttributes(['class' => 'font-bold text-lg'])
                                ->required()
                                ->helperText('Harga modal per unit produk.'),
                            
                            TextInput::make('stock')
                                ->label('Stok Awal')
                                ->numeric()
                                ->minValue(0)
                                ->default(0)
                                ->prefix('Qty')
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->hiddenOn('edit')
                                ->extraInputAttributes(['class' => 'text-primary-600 font-bold'])
                                ->helperText('Hanya diisi saat daftar produk baru.'),
                        ]),
                    ]),

                // SECTION 3: FOTO
                Section::make('Foto Produk')
                    ->description('Upload foto produk agar visual di dashboard dan laporan lebih jelas.')
                    ->icon('heroicon-m-photo')
                    ->schema([
                        FileUpload::make('image')
                            ->hiddenLabel()
                            ->image()
                            ->directory('products')
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imagePreviewHeight('250') 
                            ->required(),
                    ]),
            ]);
    }
}