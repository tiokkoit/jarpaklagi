<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StockMovementStats extends BaseWidget
{
  protected function getStats(): array
  {
    $today = Carbon::today();

    $inToday = StockMovement::where('type', 'in')->whereDate('created_at', $today)->sum('quantity');
    $outToday = StockMovement::where('type', 'out')->whereDate('created_at', $today)->sum('quantity');

    // Product with most movement today
    $mostActive = StockMovement::whereDate('created_at', $today)
      ->selectRaw('product_id, count(*) as count')
      ->groupBy('product_id')
      ->orderByDesc('count')
      ->with('product')
      ->first();

    $activeProductName = $mostActive ? $mostActive->product->name : '-';

    return [
      Stat::make('Barang Masuk (Hari Ini)', '+' . $inToday)
        ->description('Stok masuk gudang hari ini')
        ->descriptionIcon('heroicon-s-arrow-down-on-square-stack')
        ->color('success')
        ->chart([2, 10, 5, 20, $inToday]),

      Stat::make('Barang Keluar (Hari Ini)', '-' . $outToday)
        ->description('Stok keluar gudang hari ini')
        ->descriptionIcon('heroicon-s-arrow-up-on-square-stack')
        ->color('danger')
        ->chart([5, 8, 12, 15, $outToday]),

      Stat::make('Produk Teraktif', $activeProductName)
        ->description('Produk paling top hari ini')
        ->descriptionIcon('heroicon-s-fire')
        ->color('warning'),
    ];
  }
}
