<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;

use App\Models\ProductPackage;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;


class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3, // Menggunakan total 3 kolom imajiner
            ])
            ->components([

                // --- 🟢 KOLOM KIRI (2/3): INFORMASI PESANAN & PELANGGAN ---
                Group::make([
                    // SECTION 1: PRODUK
                    Section::make('Rincian Pesanan Produk')
                        ->description('Pilih paket produk yang dipesan oleh pelanggan.')
                        ->icon('heroicon-m-shopping-bag')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('product_package_id')
                                    ->label('Paket Produk')
                                    ->options(ProductPackage::pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $pkg = ProductPackage::find($state);
                                            $set('price', $pkg?->price ?? 0);
                                        }
                                    }),

                                TextInput::make('quantity')
                                    ->label('Jumlah Pesanan (Qty)')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live()
                                    ->prefix('x')
                                    ->suffix('Paket'),
                            ]),
                        ]),

                    // SECTION 2: PELANGGAN
                    Section::make('Identitas Pemesan')
                        ->description('Data lengkap pelanggan CV Agrosehat Nusantara.')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('customer_name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Misal: Budi Santoso')
                                    ->required(),

                                TextInput::make('phone')
                                    ->label('Nomor WhatsApp/HP')
                                    ->placeholder('0812xxx')
                                    ->tel()
                                    ->required(),
                            ]),

                            TextInput::make('customer_address')
                                ->label('Alamat Lengkap')
                                ->placeholder('Jl. Contoh No. 123...')
                                ->required(),

                            Grid::make(3)->schema([
                                TextInput::make('kecamatan')->label('Kecamatan')->required(),
                                TextInput::make('kota')->label('Kota/Kabupaten')->required(),
                                TextInput::make('province')->label('Provinsi')->required(),
                            ]),
                        ]),
                ])->columnSpan(['lg' => 2]),

                // --- 🔵 KOLOM KANAN (1/3): PEMBAYARAN & RINGKASAN ---
                Group::make([
                    Section::make('Detail Pembayaran')
                        ->icon('heroicon-m-credit-card')
                        ->schema([
                            DatePicker::make('order_date')
                                ->label('Tanggal Transaksi')
                                ->default(now())
                                ->required(),

                            Select::make('payment')
                                ->label('Metode Pembayaran')
                                ->options([
                                    'COD' => 'Cash on Delivery (COD)',
                                    'TRANSFER' => 'Transfer Bank',
                                ])
                                ->native(false)
                                ->required(),

                            Hidden::make('price'),
                        ]),

                    Section::make('Ringkasan Harga')
                        ->icon('heroicon-m-calculator')
                        ->schema([
                            Placeholder::make('summary_total')
                                ->hiddenLabel()
                                ->content(function (Get $get) {
                                    $price = (int) $get('price') ?? 0;
                                    $qty = (int) $get('quantity') ?? 0;
                                    $total = $price * $qty;

                                    return new HtmlString('
                                        <div class="space-y-2 border-t pt-2 mt-2">
                                            <div class="flex justify-between text-sm text-gray-500">
                                                <span>Harga Satuan Paket:</span>
                                                <span>Rp ' . number_format($price) . '</span>
                                            </div>
                                            <div class="flex justify-between text-lg font-black text-primary-700">
                                                <span>Total Harga:</span>
                                                <span>Rp ' . number_format($total) . '</span>
                                            </div>
                                        </div>
                                    ');
                                }),
                        ]),
                ])->columnSpan(['lg' => 1]),

            ]);
    }
}