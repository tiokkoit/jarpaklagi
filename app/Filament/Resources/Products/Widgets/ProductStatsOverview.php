<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProductStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    $totalProducts = Product::count();
    $totalStock = Product::sum('stock');
    $lowStockCount = Product::where('stock', '<=', 10)->count();
    $outOfStockCount = Product::where('stock', 0)->count();
    $inventoryValue = Product::select(DB::raw('SUM(hpp * stock) as total_value'))->value('total_value') ?? 0;

    return [
      Stat::make('Total SKU', $totalProducts)
        ->description('Active products')
        ->descriptionIcon('heroicon-m-cube')
        ->color('primary')
        ->chart([7, 2, 10, 3, 15, 4, 17]),

      Stat::make('Valuasi Stok', 'Rp ' . number_format($inventoryValue, 0, ',', '.'))
        ->description('Total asset value')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('success'),

      Stat::make('Stok Kritis', $lowStockCount)
        ->description($outOfStockCount . ' Barang kosong')
        ->descriptionIcon('heroicon-m-exclamation-triangle')
        ->color('danger'),
    ];
  }
}
