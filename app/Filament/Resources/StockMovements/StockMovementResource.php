<?php

namespace App\Filament\Resources\StockMovements;

use App\Filament\Resources\StockMovements\Pages\CreateStockMovement;
use App\Filament\Resources\StockMovements\Pages\EditStockMovement;
use App\Filament\Resources\StockMovements\Pages\ListStockMovements;
use App\Filament\Resources\StockMovements\Pages\ViewStockMovement;
use App\Filament\Resources\StockMovements\Schemas\StockMovementForm;
use App\Filament\Resources\StockMovements\Tables\StockMovementsTable;
use App\Models\StockMovement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Stock Movements';
    #protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return true; // ✅ Boleh create manual
    }

    public static function canEdit($record): bool
    {
        // ✅ Hanya boleh edit jika berasal dari input manual
        return $record?->reference_type === null;
    }

    public static function canDelete($record): bool
    {
        // 🚫 Tidak boleh hapus data sistem
        return $record?->reference_type === null;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return StockMovementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMovementsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockMovements::route('/'),
            'create' => CreateStockMovement::route('/create'),
            'view' => ViewStockMovement::route('/{record}'),
            'edit' => EditStockMovement::route('/{record}/edit'),
        ];
    }
}
