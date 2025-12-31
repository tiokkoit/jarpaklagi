<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InventoryHealthStats extends BaseWidget
{
  protected function getStats(): array
  {
    // 1. Total Inventory Value (Assets)
    $totalAssetValue = Product::sum(DB::raw('stock * hpp'));

    // 2. Dead Stock (No movement OUT in 30 days)
    // Find products NOT in StockMovement type 'order' or 'out' in las 30 days
    $activeProductIds = StockMovement::where('type', 'out')
      ->where('created_at', '>=', now()->subDays(30))
      ->pluck('product_id')
      ->unique();

    $deadStockCount = Product::whereNotIn('id', $activeProductIds)->where('stock', '>', 0)->count();

    // 3. Stock Turnover Ratio (Simple proxy: Total Out / Avg Stock) - simplifying to Total Units Sold
    $unitsSold30Days = StockMovement::where('type', 'out')
      ->where('reason', 'order')
      ->where('created_at', '>=', now()->subDays(30))
      ->sum('quantity');

    return [
      Stat::make('Total Asset Value', 'Rp ' . number_format($totalAssetValue, 0, ',', '.'))
        ->description('Valuasi Stok Berdasarkan HPP')
        ->color('success')
        ->icon('heroicon-m-banknotes'),

      Stat::make('Dead Stock Candidates', $deadStockCount . ' Items')
        ->description('No sales in 30 days')
        ->color('danger')
        ->icon('heroicon-m-archive-box-x-mark'),

      Stat::make('Units Sold (30 Days)', number_format($unitsSold30Days))
        ->description('Kecepatan Penjualan')
        ->color('info')
        ->icon('heroicon-m-arrow-trending-up'),
    ];
  }
}
