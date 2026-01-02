<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatusDonut extends ApexChartWidget
{
    protected static ?string $chartId = 'orderStatusDonut';
    protected static ?string $heading = 'Operational: Status Transaksi';
    protected int | string | array $columnSpan = 1;

    // Default ke 30 Hari agar data awal langsung terisi banyak
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

        // Logika Penentuan Range Tanggal
        $query = SalesReport::query();

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

        $data = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 350,
            ],
            'series' => [
                (int) ($data['SELESAI'] ?? 0),
                (int) ($data['DIKEMBALIKAN'] ?? 0),
                (int) ($data['CANCEL'] ?? 0),
            ],
            'labels' => ['SELESAI', 'DIKEMBALIKAN', 'CANCEL'],
            'colors' => ['#10b981', '#f59e0b', '#f43f5e'],
            'legend' => ['position' => 'bottom'],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%',
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'Total Order',
                                'formatter' => "function (w) { return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' Trx' }"
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}