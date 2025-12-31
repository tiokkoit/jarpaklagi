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
        ->descriptionIcon('heroicon-s-users')
        ->color('indigo')
        ->chart([1, 2, 3, 5, User::count()]),

      Stat::make('Manager', User::where('role', 'manager')->count())
        ->description('Akses Full Control')
        ->descriptionIcon('heroicon-s-shield-check')
        ->color('danger'), // Rose

      Stat::make('Admin', User::where('role', 'admin')->count())
        ->description('Administrator sistem')
        ->descriptionIcon('heroicon-s-cog-6-tooth')
        ->color('success'), // Green as requested

      Stat::make('Staf Gudang', User::where('role', 'inventory')->count())
        ->description('Officer Inventory')
        ->descriptionIcon('heroicon-s-truck')
        ->color('warning'), // Amber
    ];
  }
}
