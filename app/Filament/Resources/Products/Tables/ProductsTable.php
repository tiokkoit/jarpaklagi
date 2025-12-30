<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn; // ✅ Tambahkan ini untuk visual status
use Filament\Tables\Table;
use App\Models\Product; // ✅ Pastikan Model Product di-import

class ProductsTable
{
    // Tetap menggunakan configure(Table $table)
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Produk') // ✅ Label lebih lengkap
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->copyable()
                    ->sortable(),
                    
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                
                // ✅ STOK: Tambahkan warna dan weight
                TextColumn::make('stock')
                    ->label('Stok Saat Ini')
                    ->numeric()
                    ->sortable()
                    ->weight('bold') // Tebalkan
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger', // Merah jika habis
                        $state <= 10 => 'warning', // Kuning jika menipis (threshold bisa diubah)
                        default => 'success', // Hijau jika aman
                    }),

                // ✅ STATUS STOK: Tambahkan Ikon Visual (User Friendly)
                IconColumn::make('stock_status')
                    ->label('Status')
                    ->tooltip(fn (Product $record) => $record->stock <= 10 ? 'Stok Kritis' : 'Stok Aman')
                    ->getStateUsing(fn (Product $record): string => $record->stock <= 10 ? 'critical' : 'safe')
                    ->icon(fn (string $state): string => match ($state) {
                        'critical' => 'heroicon-o-exclamation-triangle',
                        'safe' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-information-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'critical' => 'warning',
                        'safe' => 'success',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false), // ✅ Selalu tampil

                TextColumn::make('hpp')
                    ->label('HPP')
                    ->numeric()
                    ->sortable()
                    ->money('IDR'), // ✅ Format mata uang yang konsisten

                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Gambar')
                    ->square() // ✅ Tampilkan gambar kotak
                    ->size(50), // ✅ Atur ukuran agar tidak terlalu besar

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->recordUrl(null);
    }
}