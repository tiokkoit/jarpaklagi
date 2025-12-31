<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductStockChart extends ApexChartWidget
{
  protected static ?string $chartId = 'productStockChart';
  protected static ?string $heading = 'Top 10 Level Stok Tertinggi';
  protected static ?int $sort = 2;

  protected function getOptions(): array
  {
    $products = Product::orderBy('stock', 'desc')->take(10)->get();

    $names = $products->pluck('name')->toArray();
    $stocks = $products->pluck('stock')->toArray();

    // Critical color logic
    $colors = $products->map(function ($product) {
      if ($product->stock == 0)
        return '#f43f5e'; // Rose
      if ($product->stock <= 10)
        return '#f59e0b'; // Amber
      return '#10b981'; // Emerald
    })->toArray();

    // Fail-safe colors if empty
    if (empty($colors))
      $colors = ['#10b981'];

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Stok',
          'data' => $stocks,
        ],
      ],
      'xaxis' => [
        'categories' => $names,
        'labels' => [
          'style' => ['colors' => '#9ca3af', 'fontSize' => '11px'],
        ],
        'axisBorder' => ['show' => false],
        'axisTicks' => ['show' => false],
      ],
      'yaxis' => [
        'labels' => [
          'style' => ['colors' => '#9ca3af'],
        ],
      ],
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'horizontal' => false, // Vertical bars
          'distributed' => true, // Needed for individual colors
          'columnWidth' => '50%',
        ],
      ],
      'colors' => $colors, // Apply dynamic colors
      'dataLabels' => [
        'enabled' => false,
      ],
      'grid' => [
        'show' => true,
        'borderColor' => '#f3f4f6',
        'strokeDashArray' => 4,
      ],
      'tooltip' => [
        'theme' => 'light',
        'style' => ['fontFamily' => 'inherit'],
      ],
      'legend' => ['show' => false],
    ];
  }
}
