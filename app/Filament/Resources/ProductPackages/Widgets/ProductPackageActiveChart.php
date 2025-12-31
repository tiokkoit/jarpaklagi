<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductPackageActiveChart extends ApexChartWidget
{
  /**
   * Chart Id
   *
   * @var string
   */
  protected static ?string $chartId = 'productPackageActiveChart';

  /**
   * Widget Title
   *
   * @var string|null
   */
  protected static ?string $heading = 'Status Paket Produk';

  protected static ?int $sort = 2;

  /**
   * Chart options (series, labels, types, size, animations...)
   * https://apexcharts.com/docs/options
   *
   * @return array
   */
  protected function getOptions(): array
  {
    $active = ProductPackage::where('is_active', true)->count();
    $inactive = ProductPackage::where('is_active', false)->count();

    return [
      'chart' => [
        'type' => 'pie',
        'height' => 300,
      ],
      'series' => [$active, $inactive],
      'labels' => ['Aktif', 'Tidak Aktif'],
      'legend' => [
        'position' => 'bottom',
        'labels' => [
          'colors' => '#9ca3af',
        ],
      ],
      'colors' => ['#10b981', '#6b7280'], // Green (Active), Gray (Inactive)
      'dataLabels' => [
        'enabled' => true,
      ],
    ];
  }
}
