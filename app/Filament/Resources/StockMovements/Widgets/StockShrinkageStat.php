<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StockShrinkageStat extends BaseWidget
{
  protected function getStats(): array
  {
    // 1. Calculate Loss Value (Damaged + Lost + Expired)
    // Need to join stock_movements with products to get HPP
    // Filter by reasons: damaged, lost, expired
    $lossReasons = ['damaged', 'lost', 'expired'];

    $lossData = DB::table('stock_movements')
      ->join('products', 'stock_movements.product_id', '=', 'products.id')
      ->whereIn('stock_movements.reason', $lossReasons)
      ->where('stock_movements.type', 'out') // confirmed OUT
      ->whereMonth('stock_movements.created_at', now()->month)
      ->select(DB::raw('SUM(stock_movements.quantity * products.hpp) as total_loss'), DB::raw('SUM(stock_movements.quantity) as total_qty'))
      ->first();

    $totalLossValue = $lossData->total_loss ?? 0;
    $totalLossQty = $lossData->total_qty ?? 0;

    // 2. Returns from Customer (Quality Issue)
    $returnsQty = StockMovement::where('reason', 'return_from_order')
      ->where('type', 'in')
      ->whereMonth('created_at', now()->month)
      ->sum('quantity');

    return [
      Stat::make('Stock Shrinkage Value (Bulan Ini)', 'Rp ' . number_format($totalLossValue, 0, ',', '.'))
        ->description($totalLossQty . ' items (Damaged/Lost/Expired)')
        ->descriptionIcon('heroicon-m-fire')
        ->color($totalLossValue > 0 ? 'danger' : 'success'),

      Stat::make('Customer Returns (Quality)', $returnsQty . ' Units')
        ->description('Barang masuk kembali dari customer')
        ->color($returnsQty > 0 ? 'warning' : 'success')
        ->icon('heroicon-m-arrow-path-rounded-square'),
    ];
  }
}
