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

            TextInput::make('quantity')->label('Jumlah Paket')->numeric()->required()->reactive()->afterStateUpdated(function ($state, $set, $get) {
                $price = $get('price') ?? 0;
                $set('total_price', (float)$price * (int)$state);
            }),

            TextInput::make('price')->label('Harga per Paket')->numeric()->required()->disabled(),
            TextInput::make('total_price')->label('Total Harga')->numeric()->required()->disabled(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'NEW' => 'NEW',
                    'CANCEL' => 'CANCEL',
                    'DIKIRIM' => 'DIKIRIM',
                    'SELESAI' => 'SELESAI',
                    'DIKEMBALIKAN' => 'DIKEMBALIKAN',
                ])
                ->default('NEW')
                ->required(),

            Select::make('payment')
                ->label('Payment')
                ->options([
                    'COD' => 'COD',
                    'TRANSFER' => 'TRANSFER',
                ])->required(),
        ]);
    }
}
