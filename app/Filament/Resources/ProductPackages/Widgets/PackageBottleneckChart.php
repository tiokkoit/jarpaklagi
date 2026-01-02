<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PackageBottleneckChart extends ApexChartWidget
{
    protected static ?string $chartId = 'packageBottleneckChart';
    protected static ?string $heading = 'Radar Kesiapan Produksi (Bottleneck)';
    protected int | string | array $columnSpan = 'full'; // Setengah layar agar bisa jejer

    protected function getOptions(): array
    {
        // 1. Ambil data paket aktif dan stok produk induknya
        $activePackages = ProductPackage::with('product')
            ->where('is_active', true)
            ->get();

        // 2. Hitung Kapasitas (Stok Gudang / Isi per Paket)
        $bottleneckData = $activePackages->map(function ($package) {
            $stockAvailable = $package->product->stock ?? 0;
            $canMake = $package->pcs_per_package > 0 
                ? floor($stockAvailable / $package->pcs_per_package) 
                : 0;

            return [
                'name' => $package->name,
                'qty' => $canMake
            ];
        })->sortBy('qty'); // Urutkan dari yang paling sedikit (Bottleneck Utama)

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
                    'barHeight' => '65%',
                    'distributed' => true, // Warna batang beda-beda
                ],
            ],
            'series' => [
                [
                    'name' => 'Kapasitas Produksi',
                    'data' => $bottleneckData->pluck('qty')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $bottleneckData->pluck('name')->toArray(),
                'title' => [
                    'text' => 'Maksimal Paket yang Bisa Dibuat (Unit)',
                    'style' => ['fontWeight' => 600],
                ],
            ],
            // Warna: Merah ke Biru (Visual Control)
            'colors' => [
                '#f43f5e', '#fb7185', '#fb923c', '#fcd34d', 
                '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7'
            ],
            'dataLabels' => [
                'enabled' => true,
                'textAnchor' => 'start',
                'offsetX' => 10,
                'style' => [
                    'fontSize' => '12px',
                    'colors' => ['#334155'], // Warna teks gelap biar jelas
                ],
            ],
            'subtitle' => [
                'text' => 'Data berdasarkan stok produk penyusun di gudang',
                'align' => 'right',
            ],
            'grid' => [
                'xaxis' => [
                    'lines' => ['show' => true],
                ],
            ],
        ];
    }
}