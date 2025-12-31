<?php

namespace App\Filament\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class CustomerGeographyChart extends ApexChartWidget
{
  protected static ?string $chartId = 'customerGeographyChart';
  protected static ?string $heading = 'Top Cities (Lokasi Pelanggan)';
  protected static ?int $sort = 5;

  protected function getOptions(): array
  {
    // Get Top 5 Cities by Total Revenue
    $cities = SalesReport::select('kota', DB::raw('count(*) as total_orders'), DB::raw('sum(total_price) as total_revenue'))
      ->whereNotNull('kota')
      ->where('status', 'SELESAI')
      ->groupBy('kota')
      ->orderByDesc('total_revenue')
      ->limit(10)
      ->get();

    $labels = $cities->pluck('kota')->toArray();
    $revenueData = $cities->pluck('total_revenue')->toArray();
    $orderData = $cities->pluck('total_orders')->toArray();

    return [
      'chart' => [
        'type' => 'bar',
        'height' => 350,
        'stacked' => false,
      ],
      'plotOptions' => [
        'bar' => [
          'horizontal' => true,
          'borderRadius' => 4,
        ],
      ],
      'series' => [
        [
          'name' => 'Revenue (Rp)',
          'data' => $revenueData,
        ],
      ],
      'xaxis' => [
        'categories' => $labels,
        'labels' => [
          'style' => ['fontFamily' => 'inherit'],
        ],
      ],
      'yaxis' => [
        'labels' => [
          'style' => ['fontFamily' => 'inherit'],
        ],
      ],
      'colors' => ['#10b981'],
      'dataLabels' => [
        'enabled' => true,
        'textAnchor' => 'start',
        'style' => ['colors' => ['#fff']],
        'offsetX' => 0,
      ],
      'grid' => [
        'show' => false,
      ],
    ];
  }
}
