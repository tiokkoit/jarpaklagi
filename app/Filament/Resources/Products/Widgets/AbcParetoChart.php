<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class AbcParetoChart extends ApexChartWidget
{
    protected static ?string $chartId = 'abcParetoChart';
    protected static ?string $heading = 'Analisis ABC: Prioritas Investasi Stok';
    protected int|string|array $columnSpan = 'full';

    protected function getOptions(): array
    {
        // Tarik data 10 produk dengan nilai modal tertinggi
        $data = Product::select('name', DB::raw('(hpp * stock) as investment_value'))
            ->orderByDesc('investment_value')
            ->limit(10)
            ->get();

        $names = $data->pluck('name')->toArray();
        $values = $data->pluck('investment_value')->toArray();

        // Hitung Kumulatif (%) untuk garis Pareto
        $totalValue = Product::sum(DB::raw('hpp * stock')) ?: 1;
        $runningSum = 0;
        $cumulativePerc = [];

        foreach ($values as $v) {
            $runningSum += $v;
            $cumulativePerc[] = round(($runningSum / $totalValue) * 100, 2);
        }

        return [
            'chart' => [
                'type' => 'line', // Pakai line biar bisa kombinasi Bar + Line
                'height' => 400,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Nilai Investasi (Rp)',
                    'type' => 'column', // Bar
                    'data' => $values,
                ],
                [
                    'name' => 'Persentase Kumulatif (%)',
                    'type' => 'line', // Garis Pareto
                    'data' => $cumulativePerc,
                ],
            ],
            'colors' => ['#6366f1', '#fbbf24'],
            'stroke' => [
                'width' => [0, 4],
                'curve' => 'smooth',
            ],
            'xaxis' => [
                'categories' => $names,
            ],
            'yaxis' => [
                [
                    'title' => ['text' => 'Total Modal (Rupiah)'],
                ],
                [
                    'opposite' => true,
                    'title' => ['text' => 'Kumulatif (%)'],
                    'max' => 100,
                ],
            ],
            'legend' => [
                'position' => 'top',
            ],
        ];
    }
}