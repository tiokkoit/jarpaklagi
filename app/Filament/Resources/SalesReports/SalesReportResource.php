<?php

namespace App\Filament\Resources\SalesReports;

use App\Filament\Resources\SalesReports\Pages\ListSalesReports;
use App\Filament\Resources\SalesReports\Schemas\SalesReportForm;
use App\Filament\Resources\SalesReports\Tables\SalesReportsTable;
use App\Models\SalesReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SalesReportResource extends Resource
{
    protected static ?string $model = SalesReport::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SalesReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesReportsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalesReports::route('/'),
        ];
    }

    /**
     * Only Manager and Admin can access Sales Reports
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole(['manager', 'admin']);
    }
}
