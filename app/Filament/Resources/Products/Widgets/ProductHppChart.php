<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductHppChart extends ApexChartWidget
{
    protected static ?string $chartId = 'productHppChart';
    protected static ?string $heading = 'Harga Pokok Produksi (HPP) Setiap Produk';
    protected int|string|array $columnSpan = 1;

    protected function getOptions(): array
    {
        $products = Product::orderBy('hpp', 'desc')->limit(10)->get();

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
            'colors' => ['#10b981'], 
            'series' => [
                [
                    'name' => 'HPP per Produk (Rp)',
                    'data' => $products->pluck('hpp')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $products->pluck('name')->toArray(),
                'title' => [
                    'text' => 'Harga Modal per Unit (Rp)',
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
        ];
    }
}