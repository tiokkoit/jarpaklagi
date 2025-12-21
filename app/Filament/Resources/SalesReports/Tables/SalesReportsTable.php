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
            TextColumn::make('customer_name')->label('Nama Customer'),
            TextColumn::make('productPackage.full_name')->label('Paket'),
            TextColumn::make('quantity')->label('Jumlah Paket'),
            TextColumn::make('total_price')->label('Total Harga')->money('idr'),
            BadgeColumn::make('status')->label('Status'),
        ])->defaultSort('report_date','desc');
    }
}
