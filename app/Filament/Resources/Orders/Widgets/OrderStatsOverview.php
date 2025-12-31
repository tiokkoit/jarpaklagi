<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class OrderStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    $today = Carbon::today();

    $newOrders = Order::where('status', Order::STATUS_NEW)->count();
    $sentOrders = Order::where('status', Order::STATUS_DIKIRIM)->count();
    $completedToday = Order::where('status', Order::STATUS_SELESAI)
      ->whereDate('updated_at', $today)
      ->count();

    return [
      Stat::make('Pesanan Baru (New)', $newOrders)
        ->description('Menunggu diproses')
        ->descriptionIcon('heroicon-m-sparkles') // Pulsing effect implied by color
        ->color('warning') // Amber for attention
        ->chart([5, 2, 10, 5, $newOrders]), // Dynamic look

      Stat::make('Sedang Dikirim', $sentOrders)
        ->description('Dalam perjalanan kurir')
        ->descriptionIcon('heroicon-m-truck')
        ->color('info') // Blue
        ->chart([2, 4, 6, 8, $sentOrders]),

      Stat::make('Selesai Hari Ini', $completedToday)
        ->description('Pesanan sukses hari ini')
        ->descriptionIcon('heroicon-m-check-badge')
        ->color('success'), // Green
    ];
  }
}
