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
      Stat::make('Total Users', User::count())
        ->description('Semua akun terdaftar')
        ->descriptionIcon('heroicon-m-users')
        ->color('primary'),

      Stat::make('Admins', User::where('role', 'admin')->count())
        ->description('Role Administrator')
        ->descriptionIcon('heroicon-m-shield-check')
        ->color('success'),

      Stat::make('Managers', User::where('role', 'manager')->count())
        ->description('Role Manager')
        ->descriptionIcon('heroicon-m-briefcase')
        ->color('danger'),

      Stat::make('Inventory Staff', User::where('role', 'inventory')->count())
        ->description('Role Inventory')
        ->descriptionIcon('heroicon-m-truck')
        ->color('warning'),
    ];
  }
}
