<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Total Pengguna', User::count())
        ->description('Semua akun terdaftar')
        ->descriptionIcon('heroicon-m-users')
        ->color('primary')
        ->chart([1, 2, 3, 5, User::count()]),

      Stat::make('Manager', User::where('role', 'manager')->count())
        ->description('Akses Full Control')
        ->descriptionIcon('heroicon-m-shield-check')
        ->color('danger'), // Red for high privilege

      Stat::make('Staf Gudang', User::where('role', 'inventory')->count())
        ->description('Officer Inventory')
        ->descriptionIcon('heroicon-m-truck')
        ->color('warning'), // Amber
    ];
  }
}
