<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;

class RevenueAnalyticsChart extends ApexChartWidget
{
  protected static ?string $chartId = 'revenueAnalyticsChart';
  protected static ?string $heading = 'Analisis Pendapatan & Penjualan';
  protected static ?int $sort = 2; // Position after stats overview

  protected function getOptions(): array
  {
    $days = 30;
    $revenueData = [];
    $countData = [];
    $categories = [];

    for ($i = $days; $i >= 0; $i--) {
      $date = now()->subDays($i);
      $reports = SalesReport::whereDate('report_date', $date)
        ->where('status', 'SELESAI')
        ->get();

      $revenueData[] = $reports->sum('total_price');
      $countData[] = $reports->count();
      $categories[] = $date->format('d M');
    }

    return [
      'chart' => [
        'type' => 'line', // Mixed chart types defined in series
        'height' => 350,
        'fontFamily' => 'inherit',
        'toolbar' => ['show' => false],
      ],
      'series' => [
        [
          'name' => 'Total Pendapatan (Rp)',
          'type' => 'column',
          'data' => $revenueData,
        ],
        [
          'name' => 'Jumlah Transaksi',
          'type' => 'line',
          'data' => $countData,
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
        [
          'title' => [
            'text' => 'Pendapatan',
            'style' => ['color' => '#10b981'],
          ],
          'labels' => [
            'style' => ['colors' => '#10b981', 'fontFamily' => 'inherit'],
            // JS formatter removed to fix blank chart issue
          ],
        ],
        [
          'opposite' => true,
          'title' => [
            'text' => 'Transaksi',
            'style' => ['color' => '#f59e0b'],
          ],
          'labels' => [
            'style' => ['colors' => '#f59e0b', 'fontFamily' => 'inherit'],
          ],
        ],
      ],
      'colors' => ['#10b981', '#f59e0b'], // Emerald for money, Amber for count
      'stroke' => [
        'width' => [0, 4], // 0 for column, 4 for line
        'curve' => 'smooth',
      ],
      'plotOptions' => [
        'bar' => [
          'columnWidth' => '50%',
          'borderRadius' => 4,
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
