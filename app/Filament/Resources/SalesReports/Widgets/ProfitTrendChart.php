<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitTrendChart extends ApexChartWidget
{
  protected static ?string $chartId = 'profitTrendChart';
  protected static ?string $heading = 'Revenue vs Profit Trend';
  protected static ?int $sort = 3;

  protected function getOptions(): array
  {
    $days = 30;
    $revenueSeries = [];
    $profitSeries = [];
    $categories = [];

    for ($i = $days; $i >= 0; $i--) {
      $date = now()->subDays($i);
      $dateStr = $date->toDateString();

      // Query for this day
      $dailyStats = DB::table('sales_reports')
        ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id')
        ->join('products', 'product_packages.product_id', '=', 'products.id')
        ->whereDate('sales_reports.report_date', $dateStr)
        ->where('sales_reports.status', 'SELESAI')
        ->select(
          DB::raw('SUM(sales_reports.total_price) as revenue'),
          DB::raw('SUM(products.hpp * product_packages.pcs_per_package * sales_reports.quantity) as cogs')
        )
        ->first();

      $rev = $dailyStats->revenue ?? 0;
      $cogs = $dailyStats->cogs ?? 0;
      $profit = $rev - $cogs;

      $revenueSeries[] = (int) $rev;
      $profitSeries[] = (int) $profit;
      $categories[] = $date->format('d M');
    }

    return [
      'chart' => [
        'type' => 'area',
        'height' => 300,
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Revenue',
          'data' => $revenueSeries,
        ],
        [
          'name' => 'Profit',
          'data' => $profitSeries,
        ],
      ],
      'xaxis' => [
        'categories' => $categories,
        'labels' => ['style' => ['fontFamily' => 'inherit', 'colors' => '#9ca3af']],
        'axisBorder' => ['show' => false],
        'axisTicks' => ['show' => false],
      ],
      'colors' => ['#94a3b8', '#10b981'], // Slate (Revenue), Emerald (Profit)
      'fill' => [
        'type' => 'gradient',
        'gradient' => [
          'shadeIntensity' => 1,
          'opacityFrom' => 0.45,
          'opacityTo' => 0.05,
          'stops' => [50, 100, 100]
        ],
      ],
      'stroke' => ['curve' => 'smooth', 'width' => 2],
      'dataLabels' => ['enabled' => false],
      'legend' => ['position' => 'top'],
      'grid' => ['borderColor' => '#f3f4f6', 'strokeDashArray' => 4],
    ];
  }
}
