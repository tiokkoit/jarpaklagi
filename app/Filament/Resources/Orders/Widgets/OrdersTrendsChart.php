<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;

class OrdersTrendsChart extends ApexChartWidget
{
  protected static ?string $chartId = 'ordersTrendsChart';
  protected static ?string $heading = 'Tren Pesanan (30 Hari Terakhir)';
  protected static ?int $sort = 3;

  protected function getOptions(): array
  {
    $data = [];
    $categories = [];
    $days = 30;

    for ($i = $days; $i >= 0; $i--) {
      $date = now()->subDays($i);
      $count = Order::whereDate('created_at', $date)->count();

      $data[] = $count;
      $categories[] = $date->format('d M');
    }

    return [
      'chart' => [
        'type' => 'area',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
        'animations' => [
          'enabled' => true,
          'easing' => 'easeinout',
          'speed' => 800,
        ],
      ],
      'series' => [
        [
          'name' => 'Jumlah Pesanan',
          'data' => $data,
        ],
      ],
      'xaxis' => [
        'categories' => $categories,
        'labels' => [
          'style' => [
            'colors' => '#6b7280',
            'fontFamily' => 'inherit',
          ],
        ],
        'axisBorder' => ['show' => false],
        'axisTicks' => ['show' => false],
        'tooltip' => ['enabled' => false],
      ],
      'yaxis' => [
        'labels' => [
          'style' => [
            'colors' => '#6b7280',
            'fontFamily' => 'inherit',
          ],
        ],
      ],
      'colors' => ['#8b5cf6'], // Violet-500
      'stroke' => [
        'curve' => 'smooth',
        'width' => 3,
      ],
      'fill' => [
        'type' => 'gradient',
        'gradient' => [
          'shadeIntensity' => 1,
          'opacityFrom' => 0.45,
          'opacityTo' => 0.05,
          'stops' => [50, 100, 100],
        ],
      ],
      'dataLabels' => [
        'enabled' => false,
      ],
      'grid' => [
        'show' => true,
        'borderColor' => '#f3f4f6',
        'strokeDashArray' => 4,
        'xaxis' => [
          'lines' => ['show' => false],
        ],
      ],
      'tooltip' => [
        'theme' => 'light',
        'style' => ['fontFamily' => 'inherit'],
      ],
    ];
  }
}
