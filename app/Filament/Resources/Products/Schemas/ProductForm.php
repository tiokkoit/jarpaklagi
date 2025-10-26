<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk Dasar') // ✅ Mengelompokkan field
                    ->columns(2) // ✅ Layout 2 kolom
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode Produk') // ✅ Label lebih user-friendly
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(50)
                            ->autofocus(), // ✅ Tambahkan autofokus
                        
                        TextInput::make('name')
                            ->label('Nama Produk') // ✅ Label lebih deskriptif
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Harga Pokok Produksi dan Stok')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hpp')
                            ->label('Harga Pokok Penjualan (HPP)')
                            ->numeric()
                            ->prefix('Rp') // ✅ Prefix mata uang
                            ->required(),
                        
                        // ✅ Perbaikan Logika Stok Awal
                        TextInput::make('stock')
                            ->label('Stok Awal')
                            ->numeric()
                            ->minValue(0) // ✅ Minimal 0
                            ->default(0)
                            ->required(fn (string $operation): bool => $operation === 'create') // ✅ Wajib saat Create
                            ->hiddenOn('edit') // ✅ Sembunyikan saat Edit (stok diurus oleh Movement)
                            ->helperText('Hanya diisi saat membuat produk baru. Stok selanjutnya diatur melalui mutasi.'), // ✅ Bantuan yang jelas
                    ]),

                Section::make('Gambar Produk')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Unggah Gambar Produk')
                            ->image()
                            ->directory('products')
                            ->maxSize(2048)
                            ->required()
                            ->imageResizeMode('cover') // ✅ Opsional: Atur mode resize
                            ->imageCropAspectRatio('1:1') // ✅ Opsional: Atur rasio foto
                            ->columnSpanFull(), // ✅ Gambar tampil full width
                    ]),
            ]);
    }
}
