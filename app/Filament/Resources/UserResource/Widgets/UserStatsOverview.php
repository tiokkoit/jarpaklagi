<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Semua akun terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->chart([12, 15, 14, 18, 16, 20, 24])
                ->color('indigo')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-indigo-500 shadow-lg hover:shadow-indigo-500/50',
                ]),

            Stat::make('Manager', User::where('role', 'manager')->count())
                ->description('Akses Full Control')
                ->descriptionIcon('heroicon-m-shield-check')
                ->chart([3, 3, 4, 3, 5, 4, 3])
                ->color('danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-rose-500 shadow-lg hover:shadow-rose-500/50',
                ]),

            Stat::make('Admin', User::where('role', 'admin')->count())
                ->description('Admin operasional harian')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->chart([2, 5, 3, 7, 4, 8, 10])
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-emerald-500 shadow-lg hover:shadow-emerald-500/50',
                ]),

            Stat::make('Staf Gudang', User::where('role', 'inventory')->count())
                ->description('Petugas mutasi stok gudang')
                ->descriptionIcon('heroicon-m-truck')
                ->chart([15, 12, 18, 14, 20, 16, 12])
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-amber-500 shadow-lg hover:shadow-amber-500/50',
                ]),
        ];
    }
}