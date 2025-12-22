<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_date')->label('Tanggal')->date(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => fn($state): bool => $state === 'NEW',
                        'gray' => fn($state): bool => $state === 'DIKIRIM',
                        'danger' => fn($state): bool => $state === 'CANCEL',
                        'success' => fn($state): bool => $state === 'SELESAI',
                        'warning' => fn($state): bool => $state === 'DIKEMBALIKAN',
                    ]),
                TextColumn::make('customer_name')->label('Nama Customer'),
                TextColumn::make('phone')->label('No HP'),
                Textcolumn::make('customer_address')->label('Alamat Customer'),
                Textcolumn::make('kecamatan')->label('Kecamatan'),
                Textcolumn::make('kota')->label('Kota'),
                Textcolumn::make('province')->label('Provinsi'),
                TextColumn::make('productPackage.name')->label('Paket'),
                TextColumn::make('quantity')->label('Jumlah Paket'),
                TextColumn::make('total_price')->label('Total Harga')->money('idr'),
                TextColumn::make('payment')->label('Payment'),
            ])
            ->defaultSort('order_date','desc');
    }
}
