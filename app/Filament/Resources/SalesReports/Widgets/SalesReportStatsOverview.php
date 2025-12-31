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
    $now = Carbon::now();
    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    // Previous Month for comparison
    $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
    $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

    // Revenue
    $revenueThisMonth = SalesReport::where('status', 'SELESAI')->whereBetween('report_date', [$startOfMonth, $endOfMonth])->sum('total_price');
    $revenueLastMonth = SalesReport::where('status', 'SELESAI')->whereBetween('report_date', [$startOfLastMonth, $endOfLastMonth])->sum('total_price');

    $revenueTrend = $revenueThisMonth - $revenueLastMonth;
    $revenueIcon = $revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    $revenueColor = $revenueTrend >= 0 ? 'success' : 'danger';

    // Transactions
    $trxThisMonth = SalesReport::where('status', 'SELESAI')->whereBetween('report_date', [$startOfMonth, $endOfMonth])->count();
    $trxLastMonth = SalesReport::where('status', 'SELESAI')->whereBetween('report_date', [$startOfLastMonth, $endOfLastMonth])->count();

    $trxTrend = $trxThisMonth - $trxLastMonth;
    $trxIcon = $trxTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    $trxColor = $trxTrend >= 0 ? 'success' : 'danger';

    // Total All Time
    $totalRevenue = SalesReport::where('status', 'SELESAI')->sum('total_price');

    return [
      Stat::make('Total Omset (All Time)', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
        ->description('Akumulasi pendapatan bersih')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('primary')
        ->chart([10, 15, 20, 25, 30, 35, 40]), // Mock upward trend

      Stat::make('Omset Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
        ->description($revenueTrend >= 0 ? 'Naik dari bulan lalu' : 'Turun dari bulan lalu')
        ->descriptionIcon($revenueIcon)
        ->color($revenueColor)
        ->chart([$revenueLastMonth, $revenueThisMonth]),

      Stat::make('Transaksi Bulan Ini', $trxThisMonth . ' Trx')
        ->description($trxTrend >= 0 ? 'Naik dari bulan lalu' : 'Turun dari bulan lalu')
        ->descriptionIcon($trxIcon)
        ->color($trxColor),
    ];
  }
}
