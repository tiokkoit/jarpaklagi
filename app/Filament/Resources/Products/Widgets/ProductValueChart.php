<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductValueChart extends ApexChartWidget
{
    protected static ?string $chartId = 'productValueChart';
    protected static ?string $heading = 'Total Nilai Stok Setiap Produk';
    protected int|string|array $columnSpan = 1;

    protected function getOptions(): array
    {
        $products = Product::selectRaw('*, (hpp * stock) as total_value')
            ->orderBy('total_value', 'desc')
            ->limit(10)
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
                'toolbar' => ['show' => false],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'borderRadius' => 4,
                    'barHeight' => '60%',
                ],
            ],
            'colors' => ['#6366f1'], 
            'series' => [
                [
                    'name' => 'Total Nilai Stok (Rp)',
                    'data' => $products->pluck('total_value')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $products->pluck('name')->toArray(),
                'title' => [
                    'text' => 'Total Nilai Aset (Rp)',
                    'style' => ['fontWeight' => 600],
                ],
            ],
            'grid' => [
                'show' => true,
                'borderColor' => '#f1f1f1',
                'xaxis' => [
                    'lines' => ['show' => true],
                ],
            ],
            'subtitle' => [
                'text' => 'HPP x Stok Produk',
                'align' => 'right',
            ],
        ];
    }
}