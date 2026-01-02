<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PackageCostStructureChart extends ApexChartWidget
{
    protected static ?string $chartId = 'packageCostStructureChart';
    protected static ?string $heading = 'Struktur Biaya: Modal vs Keuntungan';
    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $activePackages = ProductPackage::with('product')->where('is_active', true)->get();

        $names = [];
        $hppData = [];
        $profitData = [];

        foreach ($activePackages as $package) {
            $totalHpp = ($package->product->hpp ?? 0) * $package->pcs_per_package;
            $profit = $package->price - $totalHpp;

            $names[] = $package->name;
            $hppData[] = $totalHpp;
            $profitData[] = $profit;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
                'stacked' => true, // KUNCI: Membuat batang bertumpuk
                'toolbar' => ['show' => false],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'barHeight' => '60%',
                ],
            ],
            'colors' => ['#f59e0b', '#10b981'], // Amber (Modal) & Emerald (Profit)
            'series' => [
                [
                    'name' => 'Modal (HPP Total)',
                    'data' => $hppData,
                ],
                [
                    'name' => 'Keuntungan (Profit)',
                    'data' => $profitData,
                ],
            ],
            'xaxis' => [
                'categories' => $names,
                'title' => [
                    'text' => 'Komposisi Harga Jual (Rp)',
                    'style' => ['fontWeight' => 600],
                ],
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'left',
            ],
            'dataLabels' => [
                'enabled' => false, // Dimatikan agar tidak berantakan di layar kecil
            ],
        ];
    }
}