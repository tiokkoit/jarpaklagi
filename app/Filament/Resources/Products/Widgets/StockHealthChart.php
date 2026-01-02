<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StockHealthChart extends ApexChartWidget
{
    protected static ?string $chartId = 'stockHealthChart';
    protected static ?string $heading = 'Kesehatan Inventori (Real-time)';
    protected int|string|array $columnSpan = 'full'; 

    protected function getOptions(): array
    {
        // Hitung data berdasarkan kategori yang kamu buat tadi
        $aman = Product::where('stock', '>', 400)->count();
        $waspada = Product::whereBetween('stock', [100, 400])->count();
        $kritis = Product::where('stock', '>', 0)->where('stock', '<', 100)->count();
        $kosong = Product::where('stock', '<=', 0)->count();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => [$aman, $waspada, $kritis, $kosong],
            'labels' => ['Stok Aman', 'Status Waspada', 'Status Kritis', 'Stok Kosong'],
            'colors' => ['#10b981', '#f59e0b', '#ef4444', '#64748b'], // Hijau, Kuning, Merah, Abu-abu
            'legend' => [
                'position' => 'bottom',
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%',
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'Total SKU',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}