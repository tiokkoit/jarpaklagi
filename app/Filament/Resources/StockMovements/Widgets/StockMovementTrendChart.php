<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;

class StockMovementTrendChart extends ApexChartWidget
{
  protected static ?string $chartId = 'stockMovementTrendChart';
  protected static ?string $heading = 'Analisis Keluar Masuk Stok (7 Hari Terakhir)';
  protected static ?int $sort = 2;

  protected function getOptions(): array
  {
    $dates = collect(range(6, 0))->map(function ($daysAgo) {
      return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
    });

    $dataIn = [];
    $dataOut = [];

    foreach ($dates as $date) {
      $dataIn[] = (int) StockMovement::where('type', 'in')
        ->whereDate('created_at', $date)
        ->sum('quantity');

      $dataOut[] = (int) StockMovement::where('type', 'out')
        ->whereDate('created_at', $date)
        ->sum('quantity');
    }

    return [
      'chart' => [
        'type' => 'area',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        ['name' => 'Stok Masuk', 'data' => $dataIn],
        ['name' => 'Stok Keluar', 'data' => $dataOut],
      ],
      'xaxis' => [
        'categories' => $dates->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray(),
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
      'colors' => ['#10b981', '#f43f5e'], // Emerald (In), Rose (Out)
      'fill' => [
        'type' => 'gradient',
        'gradient' => [
          'shadeIntensity' => 1,
          'opacityFrom' => 0.5,
          'opacityTo' => 0.2, // Slightly more visible at bottom
          'stops' => [0, 90, 100],
        ],
      ],
      'stroke' => ['curve' => 'smooth', 'width' => 2],
      'dataLabels' => ['enabled' => false],
      'grid' => [
        'borderColor' => '#f3f4f6',
        'strokeDashArray' => 4,
      ],
      'tooltip' => [
        'theme' => 'light',
        'style' => ['fontFamily' => 'inherit']
      ],
    ];
  }
}
