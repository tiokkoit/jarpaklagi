<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class SalesReportStatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    $currentMonth = Carbon::now();

    $totalRevenueAllTime = SalesReport::where('status', 'SELESAI')->sum('total_price');
    $revenueThisMonth = SalesReport::where('status', 'SELESAI')
      ->whereYear('report_date', $currentMonth->year)
      ->whereMonth('report_date', $currentMonth->month)
      ->sum('total_price');

    $transactionsThisMonth = SalesReport::where('status', 'SELESAI')
      ->whereYear('report_date', $currentMonth->year)
      ->whereMonth('report_date', $currentMonth->month)
      ->count();

    return [
      Stat::make('Total Pendapatan (All Time)', 'Rp ' . number_format($totalRevenueAllTime, 0, ',', '.'))
        ->description('Total akumulasi pendapatan')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('success'),

      Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
        ->description('Omset bulan ' . $currentMonth->format('F'))
        ->descriptionIcon('heroicon-m-currency-dollar')
        ->color('primary'),

      Stat::make('Transaksi Bulan Ini', $transactionsThisMonth)
        ->description('Jumlah transaksi sukses')
        ->descriptionIcon('heroicon-m-shopping-bag')
        ->color('info'),
    ];
  }
}
