<?php

namespace App\Filament\Resources\ProductPackages\Schemas;

use App\Models\Product;
use Filament\Schemas\Schema;
use App\Models\ProductPackage;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ProductPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        // Set total kolom menjadi 3 pada layar besar (lg)
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                
                // --- BAGIAN KIRI (UTAMA) ---
                // Kita bungkus dalam Group dan beri span 2
                Group::make([
                    Section::make('Informasi Utama Paket')
                        ->description('Pilih produk dasar dan tentukan identitas paketnya.')
                        ->icon('heroicon-m-cube-transparent')
                        ->schema([
                            // Pilih Produk
                            Select::make('product_id')
                                ->label('Produk Dasar')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('name_suffix', null);
                                    $set('code_suffix', null);
                                    $set('name', null);
                                    $set('code', null);
                                })
                                ->columnSpanFull(),

                            // Grid Internal untuk Nama & Kode
                            Grid::make(2)->schema([
                                TextInput::make('name_suffix')
                                    ->label('Nama Paket')
                                    ->placeholder('Misal: Paket 1')
                                    ->prefix(fn (Get $get) => Product::find($get('product_id'))?->name . ' ' ?? '...')
                                    ->required()
                                    ->dehydrated(false)
                                    ->live(onBlur: true)
                                    ->rule(fn (Get $get, ?ProductPackage $record) => self::validateUniqueName($get, $record))
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $product = Product::find($get('product_id'));
                                        $set('name', $product ? $product->name . ' ' . $state : $state);
                                    }),

                                TextInput::make('code_suffix')
                                    ->label('Kode Paket')
                                    ->placeholder('Misal: PC01')
                                    ->prefix(fn (Get $get) => Product::find($get('product_id'))?->code . '-' ?? '...')
                                    ->required()
                                    ->dehydrated(false)
                                    ->live(onBlur: true)
                                    ->rule(fn (Get $get, ?ProductPackage $record) => self::validateUniqueCode($get, $record))
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $product = Product::find($get('product_id'));
                                        $set('code', $product ? $product->code . '-' . $state : $state);
                                    }),
                            ]),

                            // Konfigurasi Isi & Harga
                            Section::make('Konfigurasi Paket')
                                ->compact()
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('pcs_per_package')
                                            ->label('Isi (Quantity)')
                                            ->numeric()
                                            ->suffix('Pcs/Paket')
                                            ->minValue(1)
                                            ->default(1)
                                            ->required()
                                            ->live(onBlur: true)
                                            ->rule(fn (Get $get, ?ProductPackage $record) => self::validateUniquePcs($get, $record)),

                                        TextInput::make('price')
                                            ->label('Harga Jual Paket')
                                            ->prefix('Rp')
                                            ->numeric()
                                            ->required()
                                            ->extraInputAttributes(['class' => 'text-success-600 font-bold']),
                                    ]),
                                ]),
                        ]),
                ])->columnSpan(['lg' => 2]), // Jatah 2 kolom untuk kiri

                // --- BAGIAN KANAN (SIDEBAR) ---
                // Kita bungkus dalam Group dan beri span 1
                Group::make([
                    Section::make('Status Penjualan')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Tersedia untuk Dijual')
                                ->default(true)
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon('heroicon-m-check')
                                ->offIcon('heroicon-m-x-mark'),
                        ]),
                    
                    Section::make('Asisten Input')
                        ->description('Tips pengisian data paket.')
                        ->icon('heroicon-o-light-bulb')
                        ->schema([
                            // Placeholder untuk info tambahan
                        ]) 
                ])->columnSpan(['lg' => 1]), // Jatah 1 kolom untuk kanan

                // Hidden fields untuk data asli
                Hidden::make('name')->required(),
                Hidden::make('code')->required(),
            ]);
    }

    /**
     * LOGIKA VALIDASI
     */
    protected static function validateUniqueName($get, $record) {
        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
            $product = Product::find($get('product_id'));
            if (!$product || !$value) return;
            $fullName = $product->name . ' ' . $value;
            if (ProductPackage::where('name', $fullName)->when($record, fn($q) => $q->where('id', '!=', $record->id))->exists()) {
                $fail("Nama paket '{$fullName}' sudah digunakan.");
            }
        };
    }

    protected static function validateUniqueCode($get, $record) {
        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
            $product = Product::find($get('product_id'));
            if (!$product || !$value) return;
            $fullCode = $product->code . '-' . $value;
            if (ProductPackage::where('code', $fullCode)->when($record, fn($q) => $q->where('id', '!=', $record->id))->exists()) {
                $fail("Kode paket '{$fullCode}' sudah digunakan.");
            }
        };
    }

    protected static function validateUniquePcs($get, $record) {
        return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
            $productId = $get('product_id');
            if (!$productId || !$value) return;
            if (ProductPackage::where('product_id', $productId)->where('pcs_per_package', $value)->when($record, fn($q) => $q->where('id', '!=', $record->id))->exists()) {
                $fail("Sudah ada paket dengan isi {$value} pcs untuk produk ini.");
            }
        };
    }
}