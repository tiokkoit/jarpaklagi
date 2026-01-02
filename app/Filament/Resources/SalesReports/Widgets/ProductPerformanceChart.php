<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class ProductPerformanceChart extends ApexChartWidget
{
    protected static ?string $chartId = 'productPerformanceChart';
    protected static ?string $heading = 'Analisis Produk: Ranking Omset & Keuntungan';
    protected int | string | array $columnSpan = 'full';

    // Filter Default (Sinkron dengan widget lain)
    public ?string $filter = '30_days';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'yesterday' => 'Kemarin',
            '7_days' => '7 Hari Terakhir',
            '30_days' => '30 Hari Terakhir',
            'this_month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
            'year' => 'Tahun Ini',
            'all' => 'Seluruh Waktu',
        ];
    }

    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
        $now = now();

        $query = SalesReport::where('sales_reports.status', 'SELESAI')
            ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id')
            ->join('products', 'product_packages.product_id', '=', 'products.id');

        if ($activeFilter !== 'all') {
            [$start, $end] = match ($activeFilter) {
                'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
                'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
                '7_days' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
                'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
                'last_month' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
                'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
                default => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
            };
            $query->whereBetween('report_date', [$start, $end]);
        }

        // Ambil Data Ranking Paket Produk
        $data = $query->select(
                'product_packages.name as package_name',
                DB::raw('SUM(sales_reports.total_price) as total_revenue'),
                DB::raw('SUM(sales_reports.total_price - (products.hpp * product_packages.pcs_per_package * sales_reports.quantity)) as total_profit')
            )
            ->groupBy('product_packages.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 450,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Total Omset (Revenue)',
                    'data' => $data->pluck('total_revenue')->toArray(),
                ],
                [
                    'name' => 'Total Untung (Profit)',
                    'data' => $data->pluck('total_profit')->toArray(),
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'borderRadius' => 4,
                    'barHeight' => '70%',
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                ],
            ],
            'colors' => ['#6366f1', '#10b981'], // Indigo & Emerald
            'xaxis' => [
                'categories' => $data->pluck('package_name')->toArray(),
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'right',
            ],
            'dataLabels' => [
                'enabled' => false, // Dimatikan agar tidak bertumpuk di bar horizontal
            ],
            'subtitle' => [
                'text' => 'Perbandingan performa finansial antar paket produk Moringa',
                'align' => 'left',
                'margin' => 0,
                'offsetY' => -10,
                'style' => ['color' => '#94a3b8', 'fontSize' => '12px'],
            ],
            'tooltip' => [
                'theme' => 'light',
                'shared' => true,
                'intersect' => false,
            ],
        ];
    }
}