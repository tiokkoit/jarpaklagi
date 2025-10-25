<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Product Code')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('name')
                    ->label('Product Name')
                    ->unique(ignoreRecord: true)
                    ->required(),
                FileUpload::make('image')
                    ->label('Product Image')
                    ->image()
                    ->directory('products') // otomatis ke storage/app/public/products
                    ->maxSize(2048)
                    ->required(),
                TextInput::make('hpp')
                    ->label('Harga Pokok Produksi')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
