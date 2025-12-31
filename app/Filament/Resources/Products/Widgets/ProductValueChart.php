<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductValueChart extends ApexChartWidget
{
  protected static ?string $chartId = 'productValueChart';
  protected static ?string $heading = 'Valuasi Aset Produk (Top 50)';
  protected static ?int $sort = 3;

  protected function getOptions(): array
  {
    // 1. Fetch & Process Data
    $products = Product::all();

    $data = $products->map(function ($item) {
      return [
        'name' => $item->name ?? 'Unknown',
        'value' => (int) ($item->stock * $item->hpp),
      ];
    })->sortByDesc('value')->take(50); // Cap at 50 to prevent crash if 'All' is too huge

    $names = array_values($data->pluck('name')->toArray());
    $values = array_values($data->pluck('value')->toArray());

    // 2. Dynamic Height
    $minHeight = 400;
    $dynamicHeight = count($names) * 25; // Compact bars
    $height = $dynamicHeight > $minHeight ? $dynamicHeight : $minHeight;

    return [
      'chart' => [
        'type' => 'bar',
        'height' => $height,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Nilai Aset',
          'data' => $values,
        ],
      ],
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'horizontal' => true,
          'barHeight' => '60%',
          'distributed' => false,
        ],
      ],
      'xaxis' => [
        'categories' => $names,
        'labels' => [
          'style' => [
            'colors' => '#6b7280',
            'fontSize' => '11px',
            'fontWeight' => 600,
          ],
        ],
        'axisBorder' => ['show' => false],
        'axisTicks' => ['show' => false],
      ],
      'yaxis' => [
        'labels' => [
          'style' => [
            'colors' => '#6b7280',
            'fontSize' => '11px',
            'fontWeight' => 500,
          ],
        ],
      ],
      'colors' => ['#8b5cf6'],
      'fill' => [
        'type' => 'gradient',
        'gradient' => [
          'shade' => 'light',
          'type' => 'horizontal',
          'shadeIntensity' => 0.25,
          'gradientToColors' => ['#f43f5e'], // Rose
          'inverseColors' => true,
          'opacityFrom' => 0.85,
          'opacityTo' => 0.85,
          'stops' => [0, 100]
        ],
      ],
      // DISABLED JS FORMATTERS TO FIX BLANK SCREEN
      'dataLabels' => [
        'enabled' => true,
        'textAnchor' => 'start',
        'style' => [
          'colors' => ['#ffffff'],
          'fontSize' => '10px',
        ],
        'offsetX' => 0,
      ],
      'grid' => [
        'show' => true,
        'borderColor' => '#e5e7eb',
        'strokeDashArray' => 4,
      ],
    ];
  }
}
