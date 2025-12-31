<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('New Orders', Order::where('status', Order::STATUS_NEW)->count())
        ->color('primary')
        ->icon('heroicon-m-sparkles'),

      Stat::make('Processing', Order::where('status', Order::STATUS_DIKIRIM)->count())
        ->color('warning')
        ->icon('heroicon-m-truck'),

      Stat::make('Completed', Order::where('status', Order::STATUS_SELESAI)->count())
        ->color('success')
        ->icon('heroicon-m-check-badge'),
    ];
  }
}
