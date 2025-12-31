<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\SalesReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class DashboardStatsOverview extends BaseWidget
{
  protected static ?int $sort = 1;

  protected function getStats(): array
  {
    // 1. Total Revenue (This Month)
    // Adjust status check based on your valid statuses
    $currentMonthRevenue = SalesReport::whereMonth('report_date', Carbon::now()->month)
      ->where('status', 'SELESAI')
      ->sum('total_price');

    $lastMonthRevenue = SalesReport::whereMonth('report_date', Carbon::now()->subMonth()->month)
      ->where('status', 'SELESAI')
      ->sum('total_price');

    $revenueDescription = $lastMonthRevenue > 0
      ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
      : 0;
    $revenueIcon = $currentMonthRevenue >= $lastMonthRevenue ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    $revenueColor = $currentMonthRevenue >= $lastMonthRevenue ? 'success' : 'danger';
    $revenueDescText = number_format(abs($revenueDescription), 1) . '% ' . ($currentMonthRevenue >= $lastMonthRevenue ? 'increase' : 'decrease');


    // 2. New Orders (Today)
    $newOrdersCount = Order::whereDate('created_at', Carbon::today())
      ->where('status', Order::STATUS_NEW)
      ->count();

    // 3. Low Stock Products
    // Assuming 'stock' column exists in 'products' table. Adjust threshold as needed.
    $lowStockCount = Product::where('stock', '<', 10)->count();

    return [
      Stat::make('Monthly Revenue', 'Rp ' . number_format($currentMonthRevenue, 0, ',', '.'))
        ->description($revenueDescText)
        ->descriptionIcon($revenueIcon)
        ->color($revenueColor)
        ->chart([$lastMonthRevenue, $currentMonthRevenue]) // Simple tiny chart
        ->extraAttributes([
          'class' => 'cursor-pointer',
        ]),

      Stat::make('New Orders Today', $newOrdersCount)
        ->description('Orders needing attention')
        ->descriptionIcon('heroicon-m-shopping-bag')
        ->color('primary'),

      Stat::make('Low Stock Items', $lowStockCount)
        ->description('Products with < 10 stock')
        ->descriptionIcon('heroicon-m-exclamation-triangle')
        ->color('danger'),
    ];
  }
}
