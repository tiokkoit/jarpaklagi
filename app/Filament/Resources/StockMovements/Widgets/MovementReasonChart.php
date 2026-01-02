<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\StockMovement;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class MovementReasonChart extends ApexChartWidget
{
    protected static ?string $chartId = 'movementReasonChart';
    protected static ?string $heading = 'Analisis Alasan Pergerakan Stok';
    protected int | string | array $columnSpan = 1; // Setengah layar

    // Filter yang sama agar sinkron dengan Trend Chart
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

        // Mengambil jumlah transaksi per alasan
        $data = StockMovement::select('reason', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('reason')
            ->orderByDesc('count')
            ->get();

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 400,
                'toolbar' => ['show' => false],
            ],
            'series' => $data->pluck('count')->toArray(),
            'labels' => $data->pluck('reason')->map(function($reason) {
                // Merapikan tulisan alasan (misal: 'return_from_order' jadi 'Return From Order')
                return str(str_replace('_', ' ', $reason))->title();
            })->toArray(),
            'legend' => [
                'position' => 'bottom',
                'fontFamily' => 'inherit',
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '65%',
                        'labels' => [
                            'show' => true,
                            'total' => [
                                'show' => true,
                                'label' => 'All Reasons',
                                'fontWeight' => 600,
                            ],
                        ],
                    ],
                ],
            ],
            'colors' => [
                '#10b981', '#f59e0b', '#3b82f6', '#ef4444', 
                '#8b5cf6', '#ec4899', '#64748b'
            ],
            'stroke' => [
                'width' => 2,
                'colors' => ['#fff'],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
        ];
    }
}