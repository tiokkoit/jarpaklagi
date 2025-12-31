<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrderStatusChart extends ApexChartWidget
{
  /**
   * Chart Id
   *
   * @var string
   */
  protected static ?string $chartId = 'orderStatusChart';

  /**
   * Widget Title
   *
   * @var string|null
   */
  protected static ?string $heading = 'Distribusi Status Pesanan';

  protected static ?int $sort = 2;

  /**
   * Chart options (series, labels, types, size, animations...)
   * https://apexcharts.com/docs/options
   *
   * @return array
   */
  protected function getOptions(): array
  {
    $statusCounts = Order::selectRaw('status, count(*) as count')
      ->groupBy('status')
      ->pluck('count', 'status') // [NEW => 5, DIKIRIM => 2]
      ->toArray();

    // Ensure keys exist even if count is 0 for correct color mapping
    $allStatuses = [
      Order::STATUS_NEW,
      Order::STATUS_DIKIRIM,
      Order::STATUS_SELESAI,
      Order::STATUS_CANCEL,
      Order::STATUS_DIKEMBALIKAN,
    ];

    $data = [];
    $labels = [];

    foreach ($allStatuses as $status) {
      $data[] = $statusCounts[$status] ?? 0;
      $labels[] = $status;
    }

    return [
      'chart' => [
        'type' => 'donut',
        'height' => 300,
      ],
      'series' => $data,
      'labels' => $labels,
      'legend' => [
        'labels' => [
          'colors' => '#9ca3af',
          'fontWeight' => 600,
        ],
      ],
      'colors' => ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#6b7280'], // Matching typical status colors
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '60%',
            'labels' => [
              'show' => true,
              'total' => [
                'show' => true,
                'label' => 'Total',
                'color' => '#9ca3af',
              ]
            ]
          ],
        ],
      ],
    ];
  }
}
