<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;

class StockMovementTrendChart extends ApexChartWidget
{
  /**
   * Chart Id
   *
   * @var string
   */
  protected static ?string $chartId = 'stockMovementTrendChart';

  /**
   * Widget Title
   *
   * @var string|null
   */
  protected static ?string $heading = 'Trend Pergerakan Stok (7 Hari Terakhir)';

  protected static ?int $sort = 2;

  /**
   * Chart options (series, labels, types, size, animations...)
   * https://apexcharts.com/docs/options
   *
   * @return array
   */
  protected function getOptions(): array
  {
    $dates = collect(range(6, 0))->map(function ($daysAgo) {
      return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
    });

    $dataIn = [];
    $dataOut = [];

    foreach ($dates as $date) {
      $dataIn[] = StockMovement::where('type', 'in')
        ->whereDate('created_at', $date)
        ->sum('quantity');

      $dataOut[] = StockMovement::where('type', 'out')
        ->whereDate('created_at', $date)
        ->sum('quantity');
    }

    return [
      'chart' => [
        'type' => 'area',
        'height' => 300,
        'toolbar' => [
          'show' => false,
        ],
      ],
      'series' => [
        [
          'name' => 'Stok Masuk (IN)',
          'data' => $dataIn,
        ],
        [
          'name' => 'Stok Keluar (OUT)',
          'data' => $dataOut,
        ],
      ],
      'xaxis' => [
        'categories' => $dates->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
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
      'colors' => ['#10b981', '#ef4444'], // Green for IN, Red for OUT
      'fill' => [
        'type' => 'gradient',
        'gradient' => [
          'shadeIntensity' => 1,
          'opacityFrom' => 0.45,
          'opacityTo' => 0.05,
          'stops' => [50, 100, 100],
        ],
      ],
      'stroke' => [
        'curve' => 'smooth',
        'width' => 3,
      ],
      'grid' => [
        'borderColor' => '#374151',
        'strokeDashArray' => 4,
      ],
      'markers' => [
        'size' => 4,
        'colors' => ['#fff'],
        'strokeColors' => ['#10b981', '#ef4444'],
        'strokeWidth' => 2,
        'hover' => [
          'size' => 7,
        ]
      ],
    ];
  }
}
