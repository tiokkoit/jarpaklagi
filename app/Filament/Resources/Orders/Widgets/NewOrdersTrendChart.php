<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class NewOrdersTrendChart extends ApexChartWidget
{
    protected static ?string $chartId = 'newOrdersTrendChart';
    protected static ?string $heading = 'Demand Analysis: Tren Pesanan Baru';
    protected int | string | array $columnSpan = 1;

    public ?string $filter = 'week';

    protected function getFilters(): ?array {
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

        $rawData = Order::select(DB::raw("DATE(created_at) as period"), DB::raw('count(*) as total'))
            ->where('status', Order::STATUS_NEW)
            ->whereBetween('created_at', [$startDate, $now])
            ->groupBy('period')->get()->keyBy('period');

        $categories = []; $data = [];
        $period = CarbonPeriod::create($startDate, $now);
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $categories[] = $date->format('d/m');
            $data[] = $rawData->has($key) ? $rawData[$key]->total : 0;
        }

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
                'toolbar' => ['show' => false],
                'sparkline' => ['enabled' => false],
            ],
            'subtitle' => [
                'text' => 'Laju kedatangan pesanan yang harus diproses',
                'align' => 'left',
                'offsetY' => 5, // Turunkan sedikit dari Judul
                'style' => ['color' => '#64748b', 'fontSize' => '12px']
            ],
            'grid' => [
                'padding' => [
                    'top' => 30, // PENTING: Memberi ruang agar subtitle tidak tumpang tindih
                    'right' => 20,
                ],
                'strokeDashArray' => 4,
            ],
            'series' => [['name' => 'Pesanan Baru', 'data' => $data]],
            'xaxis' => [
                'categories' => $categories,
                'labels' => ['style' => ['fontSize' => '10px', 'fontWeight' => 600]],
            ],
            'yaxis' => [
                'labels' => ['style' => ['fontSize' => '10px']],
            ],
            'colors' => ['#fb923c'],
            'stroke' => ['curve' => 'smooth', 'width' => 3],
            'fill' => [
                'type' => 'gradient',
                'gradient' => ['opacityFrom' => 0.4, 'opacityTo' => 0.05],
            ],
            'dataLabels' => ['enabled' => false],
        ];
    }
}