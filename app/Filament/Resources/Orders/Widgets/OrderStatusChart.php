<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrderStatusChart extends ApexChartWidget
{
  protected static ?string $chartId = 'orderStatusChart';
  protected static ?string $heading = 'Distribusi Status Pesanan'; // Keep simple

  protected static ?int $sort = 2;

  protected function getOptions(): array
  {
    $statusCounts = Order::selectRaw('status, count(*) as count')
      ->groupBy('status')
      ->pluck('count', 'status')
      ->toArray();

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
      $labels[] = ucfirst($status);
    }

    return [
      'chart' => [
        'type' => 'donut',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => $data,
      'labels' => $labels,
      'legend' => [
        'position' => 'bottom',
        'fontFamily' => 'inherit',
        'labels' => [
          'colors' => '#6b7280',
        ],
      ],
      // New (Amber-500), Dikirim (Sky-500), Selesai (Emerald-500), Cancel (Rose-500), Dikembalikan (Slate-500)
      'colors' => ['#f59e0b', '#0ea5e9', '#10b981', '#f43f5e', '#64748b'],
      'stroke' => ['width' => 0], // Cleaner look
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '65%',
            'labels' => [
              'show' => true,
              'name' => ['show' => true, 'fontFamily' => 'inherit'],
              'value' => ['show' => true, 'fontFamily' => 'inherit'],
              'total' => [
                'show' => true,
                'label' => 'Total',
                'color' => '#6b7280',
                'fontFamily' => 'inherit',
              ]
            ]
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
