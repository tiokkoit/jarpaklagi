<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class OrderPeakTimeChart extends ApexChartWidget
{
  protected static ?string $chartId = 'orderPeakTimeChart';
  protected static ?string $heading = 'Jam Kesibukan Pesanan (Peak Hours)';
  protected static ?int $sort = 10;

  protected function getOptions(): array
  {
    // Group by Hour (00-23)
    $hourlyData = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
      ->groupBy('hour')
      ->orderBy('hour')
      ->pluck('count', 'hour')
      ->toArray();

    $hours = range(0, 23);
    $data = [];
    $categories = [];

    foreach ($hours as $hour) {
      $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
      $categories[] = $formattedHour;
      $data[] = $hourlyData[$hour] ?? 0;
    }

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 300,
      ],
      'series' => [
        [
          'name' => 'Orders',
          'data' => $data,
        ],
      ],
      'xaxis' => [
        'categories' => $categories,
        'labels' => [
          'style' => ['fontFamily' => 'inherit'],
        ],
      ],
      'colors' => ['#6366f1'],
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'columnWidth' => '60%',
        ],
      ],
      'dataLabels' => [
        'enabled' => false,
      ],
    ];
  }
}
