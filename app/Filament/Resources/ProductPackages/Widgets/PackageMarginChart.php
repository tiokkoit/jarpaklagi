<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PackageMarginChart extends ApexChartWidget
{
    protected static ?string $chartId = 'packageMarginChart';
    protected static ?string $heading = 'Presentase Margin per Paket (%)';
    // Diubah menjadi 1 agar memakan setengah layar
    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        $activePackages = ProductPackage::with('product')
            ->where('is_active', true)
            ->get();

        $packagesData = $activePackages->map(function ($package) {
            $totalHpp = ($package->product->hpp ?? 0) * $package->pcs_per_package;
            $profit = $package->price - $totalHpp;
            
            $marginPercent = ($package->price > 0) 
                ? round(($profit / $package->price) * 100, 1) 
                : 0;

            return [
                'name' => $package->name,
                'margin' => $marginPercent,
            ];
        })->sortByDesc('margin');

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
                'toolbar' => ['show' => false],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'borderRadius' => 4,
                    'barHeight' => '60%',
                    'distributed' => true,
                ],
            ],
            'series' => [
                [
                    'name' => 'Margin Keuntungan (%)',
                    'data' => $packagesData->pluck('margin')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $packagesData->pluck('name')->toArray(),
                'title' => [
                    'text' => 'Persentase Margin Keuntungan (%)',
                    'style' => [
                        'fontWeight' => 600,
                    ],
                ],
                'labels' => [
                    'style' => [
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => [
                '#10b981', '#2dd4bf', '#0ea5e9', '#3b82f6', 
                '#818cf8', '#8b5cf6', '#ec4899', '#f472b6', 
                '#f63b3b', '#fb923c', '#f59e0b', '#aa9e0f',
            ],
            'tooltip' => [
                'enabled' => true,
                'theme' => 'dark',
            ],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontSize' => '12px',
                    'colors' => ['#fff']
                ],
            ],
            'legend' => [
                'show' => false,
            ],
        ];
    }
}