<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UserRoleChart extends ApexChartWidget
{
  protected static ?string $chartId = 'user-role-chart';
  protected static ?string $heading = 'Presetase Role Pengguna';
  protected static ?int $sort = 2;
  protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 5, 
    ];

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
                'height' => 340, // Sedikit lebih tinggi agar proposional
                'toolbar' => ['show' => false],
            ],
            'series' => $counts,
            'labels' => array_map('ucfirst', $roles),
            'legend' => [
                'position' => 'bottom',
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%', // Donut sedikit lebih tipis agar elegan
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'Total',
                                'formatter' => 'function (w) { return ' . User::count() . ' }'
                            ]
                        ]
                    ],
                ],
            ],
      // Manager (Rose), Admin (Green), Inventory (Amber)
      'colors' => ['#f43f5e', '#10b981', '#f59e0b'],
      'stroke' => ['width' => 0],
      'plotOptions' => [
        'pie' => [
          'donut' => [
            'size' => '65%',
            'labels' => [
              'show' => true,
              'total' => [
                'show' => true,
                'label' => 'Total Pengguna',
                'color' => '#6b7280',
                'fontFamily' => 'inherit',
              ]
            ]
          ],
        ],
      ],
      'tooltip' => [
        'theme' => 'light',
        'style' => ['fontFamily' => 'inherit'],
      ],
      'dataLabels' => [
        'style' => ['fontFamily' => 'inherit'],
      ],
    ];
  }
}
