<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class ProductParetoChart extends ApexChartWidget
{
    protected static ?string $chartId = 'productParetoChart';
    protected static ?string $heading = 'Analisis Pareto: Kontribusi Pendapatan per Paket';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = '30_days';

    protected function getFilters(): ?array
    {
        return [
            '30_days' => '30 Hari Terakhir',
            'this_month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
            'all' => 'Seluruh Waktu',
        ];
    }

    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
        $query = SalesReport::where('status', 'SELESAI')
            ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id');

        // Filter Tanggal
        if ($activeFilter !== 'all') {
            $start = match ($activeFilter) {
                'this_month' => now()->startOfMonth(),
                'year' => now()->startOfYear(),
                default => now()->subDays(29)->startOfDay(),
            };
            $query->whereBetween('report_date', [$start, now()]);
        }

        // 1. Ambil Data Revenue per Produk
        $data = $query->select('product_packages.name', DB::raw('SUM(total_price) as revenue'))
            ->groupBy('product_packages.name')
            ->orderByDesc('revenue')
            ->get();

        $totalRevenue = $data->sum('revenue');
        $categories = [];
        $revenueValues = [];
        $cumulativePercentages = [];
        $runningSum = 0;

        // 2. Kalkulasi Kumulatif %
        foreach ($data as $item) {
            $categories[] = $item->name;
            $revenueValues[] = (float) $item->revenue;
            $runningSum += $item->revenue;
            $cumulativePercentages[] = round(($runningSum / $totalRevenue) * 100, 2);
        }

        return [
            'chart' => [
                'type' => 'line', // Mixed Chart
                'height' => 450,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Revenue (Rp)',
                    'type' => 'column', // Bar
                    'data' => $revenueValues,
                ],
                [
                    'name' => 'Persentase Kumulatif (%)',
                    'type' => 'line', // Line
                    'data' => $cumulativePercentages,
                ],
            ],
            'colors' => ['#6366f1', '#f43f5e'],
            'stroke' => ['width' => [0, 4], 'curve' => 'smooth'],
            'xaxis' => ['categories' => $categories],
            'yaxis' => [
                [
                    'title' => ['text' => 'Revenue (Nilai Rupiah)'],
                ],
                [
                    'opposite' => true,
                    'max' => 100,
                    'title' => ['text' => 'Persentase Kumulatif (%)'],
                ],
            ],
            'markers' => ['size' => 6],
            'subtitle' => [
                'text' => 'Prinsip 80/20: Identifikasi paket produk penyumbang omset terbesar',
                'align' => 'right',
                'margin' => 0,
                'offsetY' => 0,
                'style' => ['color' => '#64748b'],
            ],
        ];
    }
}