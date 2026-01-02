<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentMethodDonut extends ApexChartWidget
{
    protected static ?string $chartId = 'paymentMethodDonut';
    protected static ?string $heading = 'Financial: Metode Pembayaran';
    protected int | string | array $columnSpan = 1;

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

        $query = SalesReport::where('status', 'SELESAI'); // Fokus ke uang masuk

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

        $data = $query->select('payment', DB::raw('count(*) as count'))
            ->groupBy('payment')
            ->get();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 350,
            ],
            'series' => $data->pluck('count')->map(fn($item) => (int) $item)->toArray(),
            'labels' => $data->pluck('payment')->map(fn($item) => strtoupper($item))->toArray(),
            'colors' => ['#6366f1', '#8b5cf6', '#ec4899', '#3b82f6'],
            'legend' => ['position' => 'bottom'],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%',
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'Total Bayar',
                                'formatter' => "function (w) { return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + ' Trx' }"
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}