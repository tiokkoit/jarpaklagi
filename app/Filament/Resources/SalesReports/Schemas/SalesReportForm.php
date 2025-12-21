<?php

namespace App\Filament\Resources\SalesReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalesReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            DatePicker::make('report_date')->required()->label('Tanggal'),
            TextInput::make('customer_name')->required()->label('Nama Customer'),
            TextInput::make('customer_address')->required()->label('Alamat'),
            TextInput::make('phone')->required()->label('No HP'),
            TextInput::make('kecamatan')->required()->label('Kecamatan'),
            TextInput::make('kota')->required()->label('Kota'),
            TextInput::make('province')->required()->label('Provinsi'),
            TextInput::make('quantity')->numeric()->required()->label('Jumlah Paket'),
            TextInput::make('price')->numeric()->required()->label('Harga per Paket'),
            TextInput::make('total_price')->numeric()->required()->label('Total Harga'),
            TextInput::make('status')->required()->label('Status'),
            TextInput::make('payment')->required()->label('Payment'),
        ]);
    }
}
