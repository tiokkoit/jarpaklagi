<?php

namespace App\Filament\Resources\ProductPackages\Schemas;

use App\Models\Product;
use App\Models\ProductPackage;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class ProductPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // --- PILIH PRODUK ---
            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('name_suffix', null);
                    $set('code_suffix', null);
                    $set('name', null);
                    $set('code', null);
                }),

            // --- NAMA PAKET (INPUT) ---
            TextInput::make('name_suffix')
                ->label('Package Name')
                ->required()
                ->prefix(fn (callable $get) => Product::find($get('product_id'))?->name . ' ' ?? '')
                ->helperText('Prefix otomatis dari product. Contoh: ketik "Paket 1"')
                ->dehydrated(false)
                ->reactive()
                ->rule(function (callable $get, $state) {
                    $productId = $get('product_id');
                    $product = $productId ? Product::find($productId) : null;
                    if (! $product || ! $state) {
                        return null;
                    }

                    $fullName = $product->name . ' ' . $state;
                    $record = $get('__filament.record'); // ✅ ambil record dari Filament v4

                    return function (string $attribute, $value, \Closure $fail) use ($fullName, $record) {
                        $exists = ProductPackage::where('name', $fullName)
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Package name '{$fullName}' sudah digunakan!");
                        }
                    };
                })
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $product = Product::find($get('product_id'));
                    $set('name', $product ? $product->name . ' ' . $state : $state);
                }),

            // --- KODE PAKET (INPUT) ---
            TextInput::make('code_suffix')
                ->label('Package Code')
                ->required()
                ->prefix(fn (callable $get) => Product::find($get('product_id'))?->code . '-' ?? '')
                ->helperText('Prefix otomatis dari product. Contoh: ketik \"PC01\"')
                ->dehydrated(false)
                ->reactive()
                ->rule(function (callable $get, $state) {
                    $productId = $get('product_id');
                    $product = $productId ? Product::find($productId) : null;
                    if (! $product || ! $state) {
                        return null;
                    }

                    $fullCode = $product->code . '-' . $state;
                    $record = $get('__filament.record'); // ✅ ambil record dari Filament v4

                    return function (string $attribute, $value, \Closure $fail) use ($fullCode, $record) {
                        $exists = ProductPackage::where('code', $fullCode)
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->exists();

                        if ($exists) {
                            $fail("Package code '{$fullCode}' sudah digunakan!");
                        }
                    };
                })
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $product = Product::find($get('product_id'));
                    $set('code', $product ? $product->code . '-' . $state : $state);
                }),

            // --- FIELD HIDDEN (DISIMPAN) ---
            Hidden::make('name')->required(),
            Hidden::make('code')->required(),

            // --- FIELD TAMBAHAN ---
            TextInput::make('pcs_per_package')
                ->label('Isi per Paket')
                ->numeric()
                ->default(1)
                ->required()
                ->minValue(1),

            TextInput::make('price')
                ->label('Package Price')
                ->numeric()
                ->prefix('Rp')
                ->required()
                ->minValue(0),

            Toggle::make('is_active')
                ->label('Is Active')
                ->default(true),
        ]);
    }
}
