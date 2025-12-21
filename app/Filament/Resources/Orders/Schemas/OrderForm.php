<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\ProductPackage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            DatePicker::make('order_date')->label('Tanggal')->required(),

            Select::make('product_package_id')
                ->label('Paket Produk')
                ->options(ProductPackage::pluck('name','id'))
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $pkg = ProductPackage::find($state);
                        if ($pkg) {
                            $set('price', $pkg->price);
                        }
                    }
                }),

            TextInput::make('customer_name')->required()->label('Nama Customer'),
            TextInput::make('phone')->required()->label('No HP'),

            TextInput::make('customer_address')->required()->label('Alamat Customer'),
            TextInput::make('kecamatan')->required()->label('Kecamatan'),

            TextInput::make('kota')->required()->label('Kota'),
            TextInput::make('province')->required()->label('Provinsi'),

            TextInput::make('quantity')->label('Jumlah Paket')->numeric()->required()->reactive(),

            Select::make('payment')
                ->label('Payment')
                ->options([
                    'COD' => 'COD',
                    'TRANSFER' => 'TRANSFER',
                ])->required(),
        ]);
    }
}
