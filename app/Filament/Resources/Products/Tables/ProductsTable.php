<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn; 
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // KODE PRODUK
                TextColumn::make('code')
                    ->label('Kode SKU')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->copyable()
                    ->sortable(),
                    
                // NAMA PRODUK
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Agar nama panjang tidak memotong tabel
                
                // STOK: Sesuai Logika Inventory (SCM/OR2)
                TextColumn::make('stock')
                    ->label('Stok Produk')
                    ->numeric()
                    ->sortable()
                    ->weight('bold')
                    ->alignment('center')
                    ->color(fn (int $state): string => match (true) {
                        $state < 100 => 'danger',   // KRITIS: Merah
                        $state <= 400 => 'warning', // WASPADA: Kuning
                        default => 'success',       // AMAN: Hijau
                    })
                    ->description(fn (int $state): string => match (true) {
                        $state < 100 => 'Segera Restock!',
                        $state <= 400 => 'Pantau Ketat',
                        default => 'Stok Terjamin',
                    }),

                // STATUS VISUAL: Indikator Kondisi Persediaan
                IconColumn::make('stock_status')
                    ->label('Kondisi')
                    ->getStateUsing(fn (Product $record): string => match (true) {
                        $record->stock < 100 => 'kritis',
                        $record->stock <= 400 => 'waspada',
                        default => 'aman',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'kritis' => 'heroicon-o-fire',             // Simbol gawat
                        'waspada' => 'heroicon-o-exclamation-circle', // Simbol peringatan
                        'aman' => 'heroicon-o-check-badge',         // Simbol oke
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'kritis' => 'danger',
                        'waspada' => 'warning',
                        'aman' => 'success',
                        default => 'gray',
                    })
                    ->tooltip(fn (Product $record) => match (true) {
                        $record->stock < 100 => 'Gawat! Di bawah Safety Stock',
                        $record->stock <= 400 => 'Waspada! Masuk area Reorder Point',
                        default => 'Aman! Stok mencukupi',
                    }),

                // HPP (Kewirausahaan)
                TextColumn::make('hpp')
                    ->label('Nilai Modal(HPP)')
                    ->numeric()
                    ->sortable()
                    ->money('IDR'), 

                // GAMBAR PRODUK
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Gambar')
                    ->circular() // Biar lebih modern
                    ->size(40),

                // METADATA (Manajemen Proyek)
                TextColumn::make('updated_at')
                    ->label('Update Terakhir')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Bisa ditambahkan filter berdasarkan status stok nantinya
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->recordUrl(null);
    }
}