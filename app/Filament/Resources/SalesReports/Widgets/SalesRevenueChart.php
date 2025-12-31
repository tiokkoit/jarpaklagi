<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;

class SalesRevenueChart extends ApexChartWidget
{
  protected static ?string $chartId = 'salesRevenueChart';
  protected static ?string $heading = 'Tren Pendapatan (90 Hari Terakhir)';
  protected static ?int $sort = 1;

  protected function getOptions(): array
  {
    $reports = SalesReport::select('report_date', 'total_price', 'status')
      ->where('status', 'SELESAI')
      ->where('report_date', '>=', now()->subDays(90))
      ->orderBy('report_date')
      ->get();

    $grouped = $reports->groupBy(function ($item) {
      return Carbon::parse($item->report_date)->format('Y-m-d');
    });

    $categories = [];
    $data = [];
    $days = 90;

    for ($i = $days; $i >= 0; $i--) {
      $dateStr = now()->subDays($i)->format('Y-m-d');
      $revenue = $grouped->has($dateStr) ? $grouped->get($dateStr)->sum('total_price') : 0;

      $categories[] = Carbon::parse($dateStr)->format('d M');
      $data[] = (int) $revenue;
    }

    return [
      'chart' => [
        'type' => 'area',
        'height' => 300,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Pendapatan',
          'data' => $data,
        ],
      ],
      'xaxis' => [
        'categories' => $categories,
        'labels' => [
          'style' => ['colors' => '#9ca3af', 'fontSize' => '10px'],
        ],
        'tickAmount' => 10,
        'axisBorder' => ['show' => false],
        'axisTicks' => ['show' => false],
      ],
      'yaxis' => [
        'labels' => [
          'style' => ['colors' => '#9ca3af'],
        ],
      ],
      'colors' => ['#10b981'],
      'fill' => [
        'type' => 'gradient',
        'gradient' => [
          'shadeIntensity' => 1,
          'opacityFrom' => 0.5,
          'opacityTo' => 0.1, // Lighter bottom
          'stops' => [0, 90, 100]
        ]
      ],
      'stroke' => ['curve' => 'smooth', 'width' => 2],
      // DISABLED JS FORMATTERS TO FIX BLANK SCREEN
      'dataLabels' => ['enabled' => false],
      'grid' => [
        'show' => true,
        'borderColor' => '#f3f4f6',
        'strokeDashArray' => 4,
      ],
    ];
  }
}
