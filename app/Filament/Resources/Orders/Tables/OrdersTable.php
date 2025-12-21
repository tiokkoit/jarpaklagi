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
                TextColumn::make('customer_name')->label('Nama Customer'),
                TextColumn::make('phone')->label('No HP'),
                TextColumn::make('productPackage.full_name')->label('Paket'),
                TextColumn::make('quantity')->label('Jumlah Paket'),
                TextColumn::make('total_price')->label('Total Harga')->money('idr'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => fn($state): bool => $state === 'NEW',
                        'info' => fn($state): bool => $state === 'DIKIRIM',
                        'danger' => fn($state): bool => $state === 'CANCEL',
                        'success' => fn($state): bool => $state === 'SELESAI',
                        'warning' => fn($state): bool => $state === 'DIKEMBALIKAN',
                    ])
                    ->sortable(),
                TextColumn::make('payment')->label('Payment'),
            ])
            ->filters([
                Filter::make('status'),
            ])
            ->defaultSort('order_date','desc');
    }
}
