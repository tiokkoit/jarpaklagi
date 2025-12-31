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
        ->description('Semua varian paket')
        ->descriptionIcon('heroicon-m-archive-box')
        ->color('primary'),

      Stat::make('Paket Aktif', $activePackages)
        ->description('Siap dijual')
        ->descriptionIcon('heroicon-m-check-circle')
        ->color('success'),

      Stat::make('Rata-rata Harga', 'Rp ' . number_format($avgPrice ?? 0, 0, ',', '.'))
        ->description('Average selling price')
        ->descriptionIcon('heroicon-m-tag')
        ->color('info'),
    ];
  }
}
