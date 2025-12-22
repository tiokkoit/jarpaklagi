<?php

namespace App\Filament\Resources\SalesReports\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class SalesReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('report_date')->label('Tanggal')->date(),
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
            
        ])->defaultSort('report_date','desc');
    }
}
