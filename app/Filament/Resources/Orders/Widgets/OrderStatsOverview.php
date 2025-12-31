<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    $newOrders = Order::where('status', Order::STATUS_NEW)->count();
    $ordersToday = Order::whereDate('order_date', today())->count();
    $revenueToday = Order::whereIn('status', [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
      ->whereDate('order_date', today())
      ->sum('total_price');

    return [
      Stat::make('Pesanan Baru', $newOrders)
        ->description('Menunggu diproses')
        ->descriptionIcon('heroicon-m-sparkles')
        ->color($newOrders > 0 ? 'warning' : 'success')
        ->extraAttributes([
          'class' => 'cursor-pointer',
          'wire:click' => "\$dispatch('setStatusFilter', { status: 'new' })",
        ]),

      Stat::make('Pesanan Hari Ini', $ordersToday)
        ->description('Total order masuk hari ini')
        ->descriptionIcon('heroicon-m-calendar')
        ->color('primary'),

      Stat::make('Omset Hari Ini', 'Rp ' . number_format($revenueToday, 0, ',', '.'))
        ->description('Estimasi pendapatan hari ini')
        ->descriptionIcon('heroicon-m-currency-dollar')
        ->color('success'),
    ];
  }
}
