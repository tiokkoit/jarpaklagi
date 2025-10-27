<?php

namespace App\Filament\Resources\ProductPackages\Schemas;

use App\Models\Product;
use App\Models\ProductPackage;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([ // 🧩 tampil 2 kolom agar form tidak panjang ke bawah

            // 🟢 PILIH PRODUK
            Select::make('product_id')
                ->label('Produk')
                ->placeholder('Pilih produk...')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull() // biar full width
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Reset semua field turunan kalau produk diganti
                    $set('name_suffix', null);
                    $set('code_suffix', null);
                    $set('name', null);
                    $set('code', null);
                })
                ->hintIcon('heroicon-o-cube')
                ->helperText('Pilih produk utama sebelum membuat paket.'),

            // 🟣 NAMA PAKET
            TextInput::make('name_suffix')
                ->label('Nama Paket')
                ->placeholder('Misal: Paket 1')
                ->prefix(fn (callable $get) => Product::find($get('product_id'))?->name . ' ' ?? '')
                ->required()
                ->dehydrated(false)
                ->reactive()
                ->rule(function (callable $get, $state) {
                    $product = $get('product_id') ? Product::find($get('product_id')) : null;
                    if (! $product || ! $state) {
                        return null;
                    }

                    $fullName = $product->name . ' ' . $state;
                    $record = $get('__filament.record'); // Filament v4 context

                    return function (string $attribute, $value, \Closure $fail) use ($fullName, $record) {
                        $exists = ProductPackage::where('name', $fullName)
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Nama paket '{$fullName}' sudah digunakan!");
                        }
                    };
                })
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $product = Product::find($get('product_id'));
                    $set('name', $product ? $product->name . ' ' . $state : $state);
                })
                ->helperText('Prefix otomatis dari nama produk. Contoh hasil: Beets Powder Paket 1'),

            // 🔵 KODE PAKET
            TextInput::make('code_suffix')
                ->label('Kode Paket')
                ->placeholder('Misal: PC01')
                ->prefix(fn (callable $get) => Product::find($get('product_id'))?->code . '-' ?? '')
                ->required()
                ->dehydrated(false)
                ->reactive()
                ->rule(function (callable $get, $state) {
                    $product = $get('product_id') ? Product::find($get('product_id')) : null;
                    if (! $product || ! $state) {
                        return null;
                    }

                    $fullCode = $product->code . '-' . $state;
                    $record = $get('__filament.record'); // Filament v4 context

                    return function (string $attribute, $value, \Closure $fail) use ($fullCode, $record) {
                        $exists = ProductPackage::where('code', $fullCode)
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Kode paket '{$fullCode}' sudah digunakan!");
                        }
                    };
                })
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $product = Product::find($get('product_id'));
                    $set('code', $product ? $product->code . '-' . $state : $state);
                })
                ->helperText('Prefix otomatis dari kode produk. Contoh hasil: MOE04-PC01'),

            // 🧩 FIELD HIDDEN UNTUK PENYIMPANAN ASLI
            Hidden::make('name')->required(),
            Hidden::make('code')->required(),

            // 🟡 JUMLAH ISI PER PAKET
            TextInput::make('pcs_per_package')
                ->label('Isi per Paket')
                ->numeric()
                ->suffix('pcs')
                ->minValue(1)
                ->default(1)
                ->required()
                ->helperText('Masukkan jumlah unit dalam satu paket.'),

            // 🟠 HARGA PAKET
            TextInput::make('price')
                ->label('Harga Paket')
                ->prefix('Rp')
                ->numeric()
                ->minValue(0)
                ->required()
                ->helperText('Masukkan harga total untuk satu paket produk.'),

            // 🟢 STATUS
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true)
                ->inline(false)
                ->helperText('Nonaktifkan jika paket tidak lagi dijual.')
                ->columnSpanFull(),
        ]);
    }
}
