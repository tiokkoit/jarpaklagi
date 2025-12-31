<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UserRoleChart extends ApexChartWidget
{
  protected static ?string $chartId = 'userRoleChart';
  protected static ?string $heading = 'Distribusi Role User';
  protected static ?int $sort = 2;

  protected function getOptions(): array
  {
    $roles = ['manager', 'admin', 'inventory'];
    $counts = [];

    foreach ($roles as $role) {
      $counts[] = User::where('role', $role)->count();
    }

    return [
      'chart' => [
        'type' => 'donut',
        'height' => 300,
      ],
      'series' => $counts,
      'labels' => array_map('ucfirst', $roles),
      'legend' => [
        'position' => 'bottom',
      ],
      'colors' => ['#ef4444', '#3b82f6', '#f59e0b'], // Red (Manager), Blue (Admin), Amber (Inventory)
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '60%',
            'labels' => [
              'show' => true,
              'total' => [
                'show' => true,
                'label' => 'Total',
                'color' => '#9ca3af',
              ]
            ]
          ],
        ],
      ],
    ];
  }
}
