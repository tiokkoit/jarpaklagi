<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Support\Facades\DB;

class OrderPeakTimeChart extends ApexChartWidget
{
    protected static ?string $chartId = 'orderPeakTimeChart';
    protected static ?string $heading = 'Workload Analysis: Jam Sibuk';
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

        $hourlyData = Order::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->where('status', Order::STATUS_NEW)
            ->whereBetween('created_at', [$startDate, $now])
            ->groupBy('hour')->pluck('count', 'hour')->toArray();

        $data = []; $categories = [];
        foreach (range(0, 23) as $hour) {
            $categories[] = str_pad($hour, 2, '0', STR_PAD_LEFT);
            $data[] = $hourlyData[$hour] ?? 0;
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => ['show' => false],
            ],
            'subtitle' => [
                'text' => 'Distribusi pesanan masuk dalam 24 jam',
                'align' => 'left',
                'offsetY' => 5,
                'style' => ['color' => '#64748b', 'fontSize' => '12px']
            ],
            'grid' => [
                'show' => false,
                'padding' => [
                    'top' => 40, // Ruang ekstra untuk DataLabels agar tidak menabrak subtitle
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'columnWidth' => '70%',
                    'dataLabels' => ['position' => 'top'],
                ],
            ],
            'series' => [['name' => 'Pesanan', 'data' => $data]],
            'dataLabels' => [
                'enabled' => true,
                'offsetY' => -20, // Posisi angka di atas batang
                'style' => ['fontSize' => '10px', 'colors' => ['#475569']],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => ['style' => ['fontSize' => '9px']],
            ],
            'yaxis' => [
                'show' => false, // Bersih, tidak perlu sumbu Y karena sudah ada angka di atas batang
            ],
            'colors' => ['#6366f1'],
        ];
    }
}