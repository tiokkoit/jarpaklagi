<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockMovementStats extends BaseWidget
{
  protected function getStats(): array
  {
    $movementsInToday = StockMovement::where('type', 'in')
      ->whereDate('created_at', today())
      ->sum('quantity');

    $movementsOutToday = StockMovement::where('type', 'out')
      ->whereDate('created_at', today())
      ->sum('quantity');

    $mostActiveProduct = StockMovement::select('product_id')
      ->selectRaw('count(*) as count')
      ->groupBy('product_id')
      ->orderByDesc('count')
      ->with('product')
      ->first();

    $activeProductName = $mostActiveProduct?->product->name ?? '-';

    return [
      Stat::make('Stok Masuk Hari Ini', number_format($movementsInToday))
        ->description('Total barang restock / return')
        ->descriptionIcon('heroicon-m-arrow-down-tray')
        ->color('success'),

      Stat::make('Stok Keluar Hari Ini', number_format($movementsOutToday))
        ->description('Total barang terjual / keluar')
        ->descriptionIcon('heroicon-m-arrow-up-tray')
        ->color('danger'),

      Stat::make('Produk Ter-Aktif', $activeProductName)
        ->description('Paling sering bergerak')
        ->descriptionIcon('heroicon-m-fire')
        ->color('warning'),
    ];
  }
}
