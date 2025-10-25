<?php

namespace App\Filament\Resources\ProductPackages\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

class ProductPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    // Reset fields kalau product diganti
                    $set('name_suffix', null);
                    $set('code_suffix', null);
                }),

            TextInput::make('name_suffix')
                ->label('Package Name')
                ->required()
                ->prefix(function (callable $get) {
                    $productId = $get('product_id');
                    if ($productId) {
                        $product = \App\Models\Product::find($productId);
                        return $product ? $product->name . ' ' : '';
                    }
                    return '';
                })
                ->helperText('Prefix otomatis dari product')
                ->dehydrated(false), // Jangan save field ini

            TextInput::make('code_suffix')
                ->label('Package Code')
                ->required()
                ->prefix(function (callable $get) {
                    $productId = $get('product_id');
                    if ($productId) {
                        $product = \App\Models\Product::find($productId);
                        return $product ? $product->code . '-' : '';
                    }
                    return '';
                })
                ->helperText('Prefix otomatis dari product')
                ->dehydrated(false), // Jangan save field ini

            // Hidden fields untuk save ke database
            Hidden::make('name')
                ->dehydrateStateUsing(function (callable $get) {
                    $productId = $get('product_id');
                    $suffix = $get('name_suffix');
                    if ($productId && $suffix) {
                        $product = \App\Models\Product::find($productId);
                        return $product ? $product->name . ' ' . $suffix : $suffix;
                    }
                    return $suffix;
                }),

            Hidden::make('code')
                ->dehydrateStateUsing(function (callable $get) {
                    $productId = $get('product_id');
                    $suffix = $get('code_suffix');
                    if ($productId && $suffix) {
                        $product = \App\Models\Product::find($productId);
                        return $product ? $product->code . '-' . $suffix : $suffix;
                    }
                    return $suffix;
                }),

            TextInput::make('pcs_per_package')
                ->label('Isi per Paket')
                ->numeric()
                ->default(1)
                ->required(),

            TextInput::make('price')
                ->label('Package Price')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Toggle::make('is_active')
                ->label('Is Active')
                ->default(true),
        ]);
    }
}
