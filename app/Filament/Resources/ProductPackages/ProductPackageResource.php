<?php

namespace App\Filament\Resources\ProductPackages;

use App\Filament\Resources\ProductPackages\Pages\CreateProductPackage;
use App\Filament\Resources\ProductPackages\Pages\EditProductPackage;
use App\Filament\Resources\ProductPackages\Pages\ListProductPackages;
use App\Filament\Resources\ProductPackages\Schemas\ProductPackageForm;
use App\Filament\Resources\ProductPackages\Tables\ProductPackagesTable;
use App\Models\ProductPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductPackageResource extends Resource
{
    protected static ?string $model = ProductPackage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ProductPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductPackagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductPackages::route('/'),
            'create' => CreateProductPackage::route('/create'),
            'edit' => EditProductPackage::route('/{record}/edit'),
        ];
    }

    /**
     * Only Manager and Admin can access Product Packages
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['manager', 'admin']);
    }
}
