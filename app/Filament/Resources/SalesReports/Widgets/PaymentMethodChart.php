<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PaymentMethodChart extends ApexChartWidget
{
  protected static ?string $chartId = 'paymentMethodChart';
  protected static ?string $heading = 'Distribusi Metode Pembayaran';
  protected static ?int $sort = 2;

  protected function getOptions(): array
  {
    // 1. Fetch
    $reports = SalesReport::select('payment')->get();

    // 2. Process
    $counts = $reports->whereNotNull('payment')
      ->where('payment', '!=', '')
      ->countBy('payment')
      ->toArray();

    // 3. Prepare
    $labels = [];
    $values = [];

    foreach ($counts as $payment => $count) {
      $labels[] = ucfirst($payment);
      $values[] = (int) $count;
    }

    // Empty state
    if (empty($values)) {
      $labels = ['Belum ada data'];
      $values = [1];
      $colors = ['#e5e7eb'];
      $tooltip = false;
    } else {
      $colors = ['#3b82f6', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'];
      $tooltip = true;
    }

    return [
      'chart' => [
        'type' => 'donut',
        'height' => 300,
        'fontFamily' => 'inherit',
      ],
      'series' => $values,
      'labels' => $labels,
      'legend' => [
        'position' => 'bottom',
        'fontFamily' => 'inherit',
        'labels' => [
          'colors' => '#6b7280',
        ],
      ],
      'colors' => $colors,
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '60%',
            'labels' => [
              'show' => $tooltip,
              'total' => [
                'show' => true,
                'label' => 'Total',
                'color' => '#6b7280',
              ],
            ],
          ],
        ],
      ],
      'stroke' => [
        'show' => false,
      ],
      'dataLabels' => [
        'enabled' => $tooltip,
        'style' => [
          'fontFamily' => 'inherit',
        ],
      ],
      'tooltip' => [
        'enabled' => $tooltip,
      ],
    ];
  }
}
