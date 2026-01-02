<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductPriceRangeChart extends ApexChartWidget
{
  protected static ?string $chartId = 'productPriceRangeChart';
  protected static ?string $heading = 'Distribusi Harga Paket Produk';
  protected int | string | array $columnSpan = 'full';

  protected function getOptions(): array
  {
    // Define ranges
    $range1 = ProductPackage::where('price', '<=', 50000)->count();
    $range2 = ProductPackage::where('price', '>', 50000)->where('price', '<=', 150000)->count();
    $range3 = ProductPackage::where('price', '>', 150000)->count();

    return [
      'chart' => [
        'type' => 'donut',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [$range1, $range2, $range3],
      'labels' => ['< Rp 50k', 'Rp 50k - 150k', '> Rp 150k'],
      'legend' => [
        'position' => 'bottom',
        'fontFamily' => 'inherit',
      ],
      'colors' => ['#10b981', '#f59e0b', '#8b5cf6'], // Emerald, Amber, Violet
      'stroke' => ['width' => 0],
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '65%',
            'labels' => [
              'show' => true,
              'total' => [
                'show' => true,
                'label' => 'Total Paket',
                'fontFamily' => 'inherit',
              ],
              'name' => ['fontFamily' => 'inherit'],
              'value' => ['fontFamily' => 'inherit'],
            ],
          ],
        ],
      ],
      'dataLabels' => [
        'enabled' => true,
        'style' => ['fontFamily' => 'inherit'],
      ],
      'tooltip' => [
        'theme' => 'light',
        'style' => ['fontFamily' => 'inherit'],
      ],
    ];
  }
}
