<?php

namespace App\Filament\Widgets;

use App\Models\SalesReport;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ProfitLossOverview extends BaseWidget
{
  protected static ?int $sort = 0; // Top priority

  protected function getStats(): array
  {
    // 1. Calculate Gross Revenue (This Month)
    $currentMonthSales = SalesReport::whereMonth('report_date', Carbon::now()->month)
      ->where('status', 'SELESAI')
      ->get();

    $revenue = $currentMonthSales->sum('total_price');

    // 2. Calculate COGS (Cost of Goods Sold) based on HPP
    // We need to join with ProductPackage -> Product to get HPP
    // logic: SalesReport -> ProductPackage (pcs_per_package) -> Product (hpp)

    $cogs = DB::table('sales_reports')
      ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id')
      ->join('products', 'product_packages.product_id', '=', 'products.id')
      ->whereMonth('sales_reports.report_date', Carbon::now()->month)
      ->where('sales_reports.status', 'SELESAI')
      ->select(DB::raw('SUM(products.hpp * product_packages.pcs_per_package * sales_reports.quantity) as total_cogs'))
      ->value('total_cogs');

    $grossProfit = $revenue - $cogs;
    $margin = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;

    return [
      Stat::make('Gross Profit (Profit Kotor)', 'Rp ' . number_format($grossProfit, 0, ',', '.'))
        ->description('Revenue - HPP (Margin: ' . number_format($margin, 1) . '%)')
        ->descriptionIcon('heroicon-m-banknotes')
        ->color($grossProfit >= 0 ? 'success' : 'danger')
        ->chart([$grossProfit * 0.8, $grossProfit * 0.9, $grossProfit]), // Mock trend

      Stat::make('Estimated COGS (HPP Total)', 'Rp ' . number_format($cogs, 0, ',', '.'))
        ->description('Total Modal Barang Terjual')
        ->descriptionIcon('heroicon-m-cube')
        ->color('gray'),
    ];
  }
}
