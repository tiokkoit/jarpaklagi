<?php

namespace App\Filament\Resources\ProductPackages\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;


class ProductPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->color('info')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Package Name')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->name),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-cube'),

                TextColumn::make('pcs_per_package')
                    ->label('Pcs/Pack')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('price')
                    ->money('idr', true)
                    ->sortable()
                    ->alignRight()
                    ->description('Harga per paket'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordUrl(null) // Biar tidak klik row langsung masuk edit (lebih aman)
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary'),

                DeleteAction::make()
                    ->label('Hapus')
                    ->color('danger'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Belum ada paket produk')
            ->emptyStateDescription('Tambahkan paket baru untuk mulai mengelola variasi produk.')
            ->emptyStateIcon('heroicon-o-cube');
    }
}
