<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;

class StockActivityChart extends ApexChartWidget
{
  protected static ?string $chartId = 'stockActivityChart';
  protected static ?string $heading = 'Aktivitas Keluar/Masuk Stok (30 Hari)';
  protected static ?int $sort = 3;

  protected function getOptions(): array
  {
    $days = 30;
    $inData = [];
    $outData = [];
    $categories = [];

    for ($i = $days; $i >= 0; $i--) {
      $date = now()->subDays($i);

      // Sum quantity for IN
      $in = StockMovement::whereDate('created_at', $date)
        ->where('type', 'in')
        ->sum('quantity');

      // Sum quantity for OUT
      $out = StockMovement::whereDate('created_at', $date)
        ->where('type', 'out')
        ->sum('quantity');

      $inData[] = (int) $in;
      $outData[] = (int) $out;
      $categories[] = $date->format('d M');
    }

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 300,
        'stacked' => true,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Masuk (In)',
          'data' => $inData,
        ],
        [
          'name' => 'Keluar (Out)',
          'data' => $outData,
        ],
      ],
      'xaxis' => [
        'categories' => $categories,
        'labels' => [
          'style' => ['colors' => '#6b7280', 'fontFamily' => 'inherit'],
        ],
        'axisBorder' => ['show' => false],
        'axisTicks' => ['show' => false],
      ],
      'yaxis' => [
        'labels' => [
          'style' => ['colors' => '#6b7280', 'fontFamily' => 'inherit'],
        ],
      ],
      'colors' => ['#10b981', '#ef4444'], // Green for In, Red for Out
      'plotOptions' => [
        'bar' => [
          'borderRadius' => 4,
          'horizontal' => false,
          'columnWidth' => '50%',
        ],
      ],
      'dataLabels' => [
        'enabled' => false,
      ],
      'legend' => [
        'position' => 'top',
        'fontFamily' => 'inherit',
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
    ];
  }
}
