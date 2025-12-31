<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OperationalEfficiencyStats extends BaseWidget
{
  protected function getStats(): array
  {
    $totalOrders = Order::count();
    if ($totalOrders == 0)
      return [];

    // 1. Return Rate
    $returned = Order::where('status', Order::STATUS_DIKEMBALIKAN)->count();
    $returnRate = ($returned / $totalOrders) * 100;

    // 2. Cancel Rate
    $cancelled = Order::where('status', Order::STATUS_CANCEL)->count();
    $cancelRate = ($cancelled / $totalOrders) * 100;

    // 3. Success Rate
    $success = Order::where('status', Order::STATUS_SELESAI)->count();
    $successRate = ($success / $totalOrders) * 100;

    // 4. Average Order Value (AOV) for Completed Orders
    $totalRevenue = Order::where('status', Order::STATUS_SELESAI)->sum('total_price');
    $aov = $success > 0 ? $totalRevenue / $success : 0;

    return [
      Stat::make('Success Rate', number_format($successRate, 1) . '%')
        ->description('Orders Completed')
        ->color('success')
        ->icon('heroicon-m-check-circle'),

      Stat::make('Return Rate', number_format($returnRate, 1) . '%')
        ->description('Orders Returned')
        ->color($returnRate > 5 ? 'danger' : 'success') // Alert if > 5%
        ->icon('heroicon-m-arrow-path'),

      Stat::make('Avg. Order Value (AOV)', 'Rp ' . number_format($aov, 0, ',', '.'))
        ->description('Rata-rata per Transaksi')
        ->color('primary')
        ->icon('heroicon-m-currency-dollar'),
    ];
  }
}
