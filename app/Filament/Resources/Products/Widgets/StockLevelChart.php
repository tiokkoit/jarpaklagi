<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StockLevelChart extends ApexChartWidget
{
    protected static ?string $chartId = 'stockLevelChart';
    protected static ?string $heading = 'Monitoring Stok Produk (Real-time)';
    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        // Ambil 10 produk dengan stok terendah agar fokus pada "problem"
        $products = Product::orderBy('stock', 'asc')->limit(10)->get();

        $stocks = $products->pluck('stock')->toArray();
        
        // LOGIKA TI: Warna dinamis berdasarkan kategori yang kamu buat
        $colors = array_map(function($stock) {
            if ($stock < 100) return '#ef4444'; // Red (Danger)
            if ($stock <= 400) return '#f59e0b'; // Amber (Warning)
            return '#10b981'; // Emerald (Safe)
        }, $stocks);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 450,
                'toolbar' => ['show' => false],
                'fontFamily' => 'inherit',
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'distributed' => true, // Mengizinkan warna tiap batang beda-beda
                    'barHeight' => '70%',
                    'borderRadius' => 4,
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'colors' => $colors, // Memasukkan array warna dinamis
            'series' => [
                [
                    'name' => 'Stok Produk',
                    'data' => $stocks,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'offsetX' => 30,
                'style' => [
                    'fontSize' => '12px',
                    'colors' => ['#334155'],
                ],
            ],
            'xaxis' => [
                'categories' => $products->pluck('name')->toArray(),
                'labels' => ['style' => ['fontWeight' => 600]],
            ],
            'grid' => [
                'xaxis' => [
                    'lines' => ['show' => true],
                ],
            ],
            // ANNOTATIONS: Penanda batas aman/kritis
            'annotations' => [
                'xaxis' => [
                    [
                        'x' => 100,
                        'borderColor' => '#ef4444',
                        'strokeDashArray' => 4,
                        'label' => [
                            'borderColor' => '#ef4444',
                            'style' => ['color' => '#fff', 'background' => '#ef4444'],
                            'text' => 'Safety Stock (100)',
                        ],
                    ],
                    [
                        'x' => 400,
                        'borderColor' => '#10b981',
                        'strokeDashArray' => 4,
                        'label' => [
                            'borderColor' => '#10b981',
                            'style' => ['color' => '#fff', 'background' => '#10b981'],
                            'text' => 'Reorder Point (400)',
                        ],
                    ],
                ],
            ],
            'tooltip' => [
                'theme' => 'light',
                'y' => [
                    'title' => ['formatter' => "() => 'Jumlah Stok: '"],
                ],
            ],
        ];
    }
}