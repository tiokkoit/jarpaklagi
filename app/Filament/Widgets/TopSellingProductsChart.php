<?php

namespace App\Filament\Widgets;

use App\Models\SalesReport;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TopSellingProductsChart extends ApexChartWidget
{
  /**
   * Chart Id
   *
   * @var string
   */
  protected static ?string $chartId = 'topSellingProductsChart';

  /**
   * Widget Title
   *
   * @var string|null
   */
  protected static ?string $heading = 'Top 5 Selling Products';

  protected static ?int $sort = 4;

  /**
   * Chart options (series, labels, types, size, animations...)
   * https://apexcharts.com/docs/options
   *
   * @return array
   */
  protected function getOptions(): array
  {
    // Join SalesReports -> ProductPackage -> Product
    // to get product names and sum quantity
    $topProducts = SalesReport::select(
      'products.name as product_name',
      DB::raw('SUM(sales_reports.quantity) as total_qty')
    )
      ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id')
      ->join('products', 'product_packages.product_id', '=', 'products.id')
      ->where('sales_reports.status', 'SELESAI')
      ->groupBy('products.name')
      ->orderByDesc('total_qty')
      ->limit(5)
      ->get();

    $names = $topProducts->pluck('product_name')->toArray();
    $quantities = $topProducts->pluck('total_qty')->toArray();

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 300,
      ],
      'series' => [
        [
          'name' => 'Quantity Sold',
          'data' => $quantities,
        ],
      ],
      'xaxis' => [
        'categories' => $names,
        'labels' => [
          'style' => [
            'fontFamily' => 'inherit',
          ],
        ],
      ],
      'yaxis' => [
        'labels' => [
          'style' => [
            'fontFamily' => 'inherit',
          ],
        ],
      ],
      'colors' => ['#6366f1'], // Indigo
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'horizontal' => true,
        ],
      ],
      'dataLabels' => [
        'enabled' => false,
      ],
      'grid' => [
        'show' => false,
      ],
    ];
  }
}
