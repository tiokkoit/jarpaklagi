<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class TopActiveSkuChart extends ApexChartWidget
{
    protected static ?string $chartId = 'topActiveSkuChart';
    protected static ?string $heading = 'Produk Paling Aktif (Fast-Moving)';
    protected int | string | array $columnSpan = 1; // Setengah layar agar simetris dengan Reason Chart

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
        $now = now();

        $startDate = match ($activeFilter) {
            'today' => $now->copy()->startOfDay(),
            'week' => $now->copy()->startOfWeek(),
            'year' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfMonth(),
        };

        // Ambil 10 produk dengan total pergerakan (In + Out) terbesar
        $data = StockMovement::select('product_id', DB::raw('SUM(quantity) as total_volume'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('product_id')
            ->orderByDesc('total_volume')
            ->limit(10)
            ->with('product')
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 380,
                'toolbar' => ['show' => false],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true, // Horizontal agar nama produk panjang tidak terpotong
                    'borderRadius' => 4,
                    'barHeight' => '70%',
                    'distributed' => true, // Warna batang beda-beda agar estetik
                ],
            ],
            'series' => [
                [
                    'name' => 'Volume Mutasi (Unit)',
                    'data' => $data->pluck('total_volume')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $data->pluck('product.name')->toArray(),
                'title' => [
                    'text' => 'Total Unit Bergerak (In + Out)',
                    'style' => ['fontWeight' => 600]
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => ['fontWeight' => 600],
                ],
            ],
            // Palette warna profesional (Indigo ke Violet)
            'colors' => [
                '#6366f1', '#4f46e5', '#4338ca', '#3730a3', '#312e81',
                '#818cf8', '#a5b4fc', '#c7d2fe', '#e0e7ff', '#6366f1'
            ],
            'legend' => [
                'show' => false,
            ],
            'grid' => [
                'xaxis' => [
                    'lines' => ['show' => true],
                ],
            ],
            'tooltip' => [
                'enabled' => true,
                'theme' => 'light',
            ],
        ];
    }
}