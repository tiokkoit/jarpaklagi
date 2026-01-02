<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PackageProfitNominalChart extends ApexChartWidget
{
    protected static ?string $chartId = 'packageProfitNominalChart';
    protected static ?string $heading = 'Margin Keuntungan per Paket (Rupiah)';
    // Diubah menjadi 1 agar memakan setengah layar
    protected int | string | array $columnSpan = 1;

    protected function getOptions(): array
    {
        $activePackages = ProductPackage::with('product')
            ->where('is_active', true)
            ->get();

        $packagesData = $activePackages->map(function ($package) {
            $totalHpp = ($package->product->hpp ?? 0) * $package->pcs_per_package;
            $profitNominal = $package->price - $totalHpp;

            return [
                'name' => $package->name,
                'profit' => $profitNominal,
            ];
        })->sortByDesc('profit');

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
                    'name' => 'Keuntungan (Rp)',
                    'data' => $packagesData->pluck('profit')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $packagesData->pluck('name')->toArray(),
                'title' => [
                    'text' => 'Nominal Margin Keuntungan (Rp)',
                    'style' => ['fontWeight' => 600],
                ],
                'labels' => [
                    'style' => ['fontWeight' => 600],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => ['fontWeight' => 600],
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
                'textAnchor' => 'start',
                'style' => [
                    'fontSize' => '12px',
                    'colors' => ['#fff'],
                ],
                'offsetX' => 10,
            ],
            'legend' => [
                'show' => false,
            ],
            'grid' => [
                'xaxis' => [
                    'lines' => ['show' => true],
                ],
            ],
        ];
    }
}