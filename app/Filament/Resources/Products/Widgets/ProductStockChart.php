<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductStockChart extends ApexChartWidget
{
  /**
   * Chart Id
   *
   * @var string
   */
  protected static ?string $chartId = 'productStockChart';

  /**
   * Widget Title
   *
   * @var string|null
   */
  protected static ?string $heading = 'Top 10 Inventory Levels';

  /**
   * Sort
   */
  protected static ?int $sort = 2;

  /**
   * Chart options (series, labels, types, size, animations...)
   * https://apexcharts.com/docs/options
   *
   * @return array
   */
  protected function getOptions(): array
  {
    $products = Product::orderBy('stock', 'desc')->take(10)->get();

    $names = $products->pluck('name')->toArray();
    $stocks = $products->pluck('stock')->toArray();
    $colors = $products->map(function ($product) {
      if ($product->stock == 0)
        return '#ef4444'; // red
      if ($product->stock <= 10)
        return '#f59e0b'; // amber
      return '#10b981'; // emerald
    })->toArray();

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 300,
      ],
      'series' => [
        [
          'name' => 'Stock Quantity',
          'data' => $stocks,
        ],
      ],
      'xaxis' => [
        'categories' => $names,
        'labels' => [
          'style' => [
            'colors' => '#9ca3af',
            'fontWeight' => 600,
          ],
        ],
      ],
      'yaxis' => [
        'labels' => [
          'style' => [
            'colors' => '#9ca3af',
            'fontWeight' => 600,
          ],
        ],
      ],
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'horizontal' => false,
          'distributed' => true,
        ],
      ],
      'colors' => $colors,
      'dataLabels' => [
        'enabled' => false,
      ],
      'grid' => [
        'show' => false,
      ],
    ];
  }
}
