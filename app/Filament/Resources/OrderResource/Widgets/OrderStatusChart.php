<?php

namespace App\Filament\Resources\OrderResource\Widgets;

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
  protected static ?string $heading = 'Order Status Distribution';

  /**
   * Chart options (series, labels, types, size, animations...)
   * https://apexcharts.com/docs/options
   *
   * @return array
   */
  protected function getOptions(): array
  {
    $statuses = [
      Order::STATUS_NEW,
      Order::STATUS_DIKIRIM,
      Order::STATUS_SELESAI,
      Order::STATUS_CANCEL,
      Order::STATUS_DIKEMBALIKAN,
    ];

    $data = [];
    foreach ($statuses as $status) {
      $data[] = Order::where('status', $status)->count();
    }

    return [
      'chart' => [
        'type' => 'donut',
        'height' => 300,
      ],
      'series' => $data,
      'labels' => $statuses,
      'legend' => [
        'position' => 'bottom',
        'fontFamily' => 'inherit',
      ],
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '50%',
          ],
        ],
      ],
      'colors' => ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#6b7280'], // Blue, Amber, Emerald, Red, Gray
    ];
  }
}
