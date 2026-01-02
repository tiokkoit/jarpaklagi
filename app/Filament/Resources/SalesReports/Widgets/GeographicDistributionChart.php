<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class GeographicDistributionChart extends ApexChartWidget
{
    protected static ?string $chartId = 'geographicDistributionChart';
    protected static ?string $heading = 'Market Analysis: Sebaran Transaksi per Kota';
    protected int | string | array $columnSpan = 'full';

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

        $query = SalesReport::where('status', 'SELESAI');

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

        $data = $query->select('kota', DB::raw('count(*) as total_trx'))
            ->groupBy('kota')
            ->orderByDesc('total_trx')
            ->limit(15)
            ->get();

        $formattedData = $data->map(function ($item) {
            return [
                'x' => $item->kota,
                'y' => (int) $item->total_trx,
            ];
        })->toArray();

        return [
            'chart' => [
                'type' => 'treemap',
                'height' => 450,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Total Transaksi per Kota',
                    'data' => $formattedData,
                ],
            ],
            // 15 Warna Premium yang Berbeda
            'colors' => [
                '#6366f1', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6',
                '#06b6d4', '#3b82f6', '#ec4899', '#f97316', '#14b8a6',
                '#facc15', '#fb7185', '#c084fc', '#22d3ee', '#4ade80'
            ],
            'plotOptions' => [
                'treemap' => [
                    'distributed' => true,
                    'enableShades' => true,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
                'style' => [
                    'fontSize' => '12px',
                    'fontWeight' => 'bold',
                ],
            ],
            'tooltip' => [
                'theme' => 'light',
                'enabled' => true,
            ],
        ];
    }
}