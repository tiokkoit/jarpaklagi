<?php

namespace App\Filament\Resources\ProductPackages\Schemas;

use App\Models\Product;
use Filament\Schemas\Schema;
use App\Models\ProductPackage;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ProductPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([

            // 🟢 PILIH PRODUK
            Select::make('product_id')
                ->label('Produk')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull()
                ->live()
                ->afterStateUpdated(function (Set $set) {
                    $set('name_suffix', null);
                    $set('code_suffix', null);
                    $set('name', null);
                    $set('code', null);
                })
                ->hintIcon('heroicon-o-cube'),

            // 🟣 NAMA PAKET
            TextInput::make('name_suffix')
                ->label('Nama Paket')
                ->placeholder('Misal: Paket 1')
                ->prefix(fn (Get $get) => Product::find($get('product_id'))?->name . ' ' ?? '')
                ->required()
                ->dehydrated(false)
                ->live(onBlur: true)
                ->rule(function (Get $get, ?ProductPackage $record) { // INJEKSI $record DI SINI
                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                        $productId = $get('product_id');
                        $product = $productId ? Product::find($productId) : null;
                        
                        if (!$product || !$value) return;

                        $fullName = $product->name . ' ' . $value;

                        $exists = ProductPackage::where('name', $fullName)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id)) // Abaikan ID sendiri jika sedang edit
                            ->exists();

                        if ($exists) {
                            $fail("Nama paket '{$fullName}' sudah digunakan!");
                        }
                    };
                })
                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                    $product = Product::find($get('product_id'));
                    $set('name', $product ? $product->name . ' ' . $state : $state);
                }),

            // 🔵 KODE PAKET
            TextInput::make('code_suffix')
                ->label('Kode Paket')
                ->placeholder('Misal: PC01')
                ->prefix(fn (Get $get) => Product::find($get('product_id'))?->code . '-' ?? '')
                ->required()
                ->dehydrated(false)
                ->live(onBlur: true)
                ->rule(function (Get $get, ?ProductPackage $record) { // INJEKSI $record DI SINI
                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                        $productId = $get('product_id');
                        $product = $productId ? Product::find($productId) : null;

                        if (!$product || !$value) return;

                        $fullCode = $product->code . '-' . $value;

                        $exists = ProductPackage::where('code', $fullCode)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Kode paket '{$fullCode}' sudah digunakan!");
                        }
                    };
                })
                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                    $product = Product::find($get('product_id'));
                    $set('code', $product ? $product->code . '-' . $state : $state);
                }),

            Hidden::make('name')->required(),
            Hidden::make('code')->required(),

            // 🟡 JUMLAH ISI PER PAKET (Ditambah validasi unik per product_id)
            TextInput::make('pcs_per_package')
                ->label('Isi per Paket')
                ->numeric()
                ->suffix('pcs')
                ->minValue(1)
                ->default(1)
                ->required()
                ->live(onBlur: true)
                ->rule(function (Get $get, ?ProductPackage $record) { // VALIDASI UNIK PER PRODUK
                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                        $productId = $get('product_id');
                        if (!$productId || !$value) return;

                        $exists = ProductPackage::where('product_id', $productId)
                            ->where('pcs_per_package', $value)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Paket dengan isi {$value} pcs untuk produk ini sudah terdaftar!");
                        }
                    };
                }),

            // 🟠 HARGA PAKET
            TextInput::make('price')
                ->label('Harga Paket')
                ->prefix('Rp')
                ->numeric()
                ->minValue(0)
                ->required(),

            // 🟢 STATUS
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true)
                ->inline(false)
                ->columnSpanFull(),
        ]);
    }
}