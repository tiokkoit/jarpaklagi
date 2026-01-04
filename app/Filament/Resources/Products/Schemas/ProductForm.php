<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'lg' => 3, // Menggunakan total 3 kolom untuk sistem Grid
            ])
            ->components([
                
                // --- BAGIAN KIRI: UTAMA (IDENTITAS & MODAL) ---
                Group::make([
                    // SECTION 1: IDENTITAS
                    Section::make('Identitas Produk')
                        ->description('Atur kode SKU dan nama produk.')
                        ->icon('heroicon-m-identification')
                        ->collapsible()
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('code')
                                    ->label('Kode SKU')
                                    ->placeholder('Contoh: MOE01')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(50)
                                    ->autofocus()
                                    ->validationMessages([
                                        'required' => 'Kode SKU wajib diisi.',
                                        'unique' => 'Kode SKU ini sudah digunakan.',
                                    ])
                                    ->helperText('Gunakan kode unik untuk mempermudah tracking.'),
                                
                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->placeholder('Contoh: Moera Infusion')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255)
                                    ->validationMessages([
                                        'required' => 'Nama produk tidak boleh kosong.',
                                        'unique' => 'Nama produk sudah digunakan.',
                                    ]),
                            ]),
                        ]),

                    // SECTION 2: MODAL & STOK
                    Section::make('Modal & Stok Awal')
                        ->description('Isi harga modal (HPP) dan jumlah produk yang masuk pertama kali ke sistem.')
                        ->icon('heroicon-m-banknotes')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('hpp')
                                    ->label('Harga Modal (HPP)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->extraInputAttributes(['class' => 'font-bold text-lg text-success-600'])
                                    ->validationMessages([
                                        'required' => 'Harga modal wajib diisi.',
                                    ]),
                                
                                TextInput::make('stock')
                                    ->label('Stok Awal')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->prefix('Qty')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->hiddenOn('edit')
                                    ->extraInputAttributes(['class' => 'font-bold text-primary-600'])
                                    ->helperText('Hanya diisi saat daftar produk baru.'),
                            ]),
                        ]),
                ])->columnSpan(['lg' => 2]), // Mengambil 2/3 lebar layar

                // --- BAGIAN KANAN: SIDEBAR (FOTO & INFO) ---
                Group::make([
                    Section::make('Foto Produk')
                        ->description('Upload foto produk agar visual di dashboard dan laporan lebih jelas.')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            FileUpload::make('image')
                                ->hiddenLabel()
                                ->image()
                                ->directory('products')
                                ->imageEditor()
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('1:1')
                                ->imagePreviewHeight('250')
                                ->required()
                                ->validationMessages([
                                    'required' => 'Foto produk wajib diupload untuk identifikasi visual.',
                                ]),
                        ]),

                    Section::make('Informasi Tambahan')
                        ->compact()
                        ->schema([
                            Placeholder::make('created_at')
                                ->label('Terdaftar Sejak')
                                ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? '-'),
                            
                            Placeholder::make('updated_at')
                                ->label('Terakhir Diperbarui')
                                ->content(fn ($record) => $record?->updated_at?->diffForHumans() ?? '-'),
                        ])->hiddenOn('create'),
                ])->columnSpan(['lg' => 1]), // Mengambil 1/3 lebar layar
            ]);
    }
}