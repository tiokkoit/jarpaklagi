<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductValueChart extends ApexChartWidget
{
  protected static ?string $chartId = 'productValueChart';
  protected static ?string $heading = 'Top 10 Aset Terbesar';
  protected static ?int $sort = 3;

  protected function getOptions(): array
  {
    // 1. Fetch Real Data
    $products = Product::all();

    $data = $products->map(function ($item) {
      return [
        'name' => $item->name ?? 'Unknown',
        'value' => (int) ($item->stock * $item->hpp),
      ];
    })->sortByDesc('value')->take(10);

    // Fail-safe for empty data
    if ($data->isEmpty()) {
      return [
        'chart' => ['type' => 'bar', 'height' => 300],
        'series' => [],
        'title' => ['text' => 'Belum ada data produk', 'align' => 'center'],
      ];
    }

    $names = array_values($data->pluck('name')->toArray());
    $values = array_values($data->pluck('value')->toArray());

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Valuasi',
          'data' => $values,
        ],
      ],
      'xaxis' => [
        'categories' => $names,
        'labels' => [
          'style' => ['colors' => '#9ca3af'],
        ],
      ],
      'yaxis' => [
        'labels' => [
          'style' => ['colors' => '#9ca3af'],
        ],
      ],
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'horizontal' => true,
          'distributed' => true,
        ],
      ],
      // Removed JS formatter to prevent rendering crash
      'dataLabels' => [
        'enabled' => true,
      ],
      'colors' => ['#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#ef4444', '#f97316', '#f59e0b', '#84cc16'],
      'legend' => ['show' => false],
    ];
  }
}
