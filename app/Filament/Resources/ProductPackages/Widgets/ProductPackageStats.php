<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductPackageStats extends BaseWidget
{
  protected function getStats(): array
  {
    $activePackages = ProductPackage::where('is_active', true)->count();
    $totalPackages = ProductPackage::count();
    $avgPrice = ProductPackage::where('is_active', true)->avg('price');

    return [
      Stat::make('Total Paket', $totalPackages)
        ->description('Varian paket tersedia')
        ->descriptionIcon('heroicon-m-archive-box')
        ->color('indigo')
        ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

      Stat::make('Paket Aktif', $activePackages)
        ->description('Paket siap dipasarkan')
        ->descriptionIcon('heroicon-m-check-badge')
        ->color('success')
        ->chart([3, 5, 3, 6, 3, 5, 3]),

      Stat::make('Rata-rata Harga', 'Rp ' . number_format($avgPrice ?? 0, 0, ',', '.'))
        ->description('Nilai rata-rata paket')
        ->descriptionIcon('heroicon-m-currency-dollar')
        ->color('warning')
        ->chart([5, 6, 7, 8, 9, 10, 11]),
    ];
  }
}
