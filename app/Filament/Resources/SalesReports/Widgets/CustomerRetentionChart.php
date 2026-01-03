<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class CustomerRetentionChart extends ApexChartWidget
{
    protected static ?string $chartId = 'customerRetentionChart';
    protected static ?string $heading = 'Loyalty Analytics: Pelanggan Baru vs Repeat Order';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = '30_days';

    protected function getFilters(): ?array
    {
        return [
            '7_days' => '7 Hari Terakhir',
            '30_days' => '30 Hari Terakhir',
            'this_month' => 'Bulan Ini',
        ];
    }

    protected function getOptions(): array
    {
        $startDate = $this->filter === '7_days' ? now()->subDays(6) : ( $this->filter === 'this_month' ? now()->startOfMonth() : now()->subDays(29) );
        $endDate = now();

        $categories = [];
        $newData = [];
        $returningData = [];

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $categories[] = $date->translatedFormat('d M');

            // Ambil HP yang transaksi di tanggal ini
            $phonesToday = SalesReport::whereDate('report_date', $formattedDate)
                ->pluck('phone')
                ->unique();

            $newCount = 0;
            $returningCount = 0;

            foreach ($phonesToday as $phone) {
                // Cek apakah pernah transaksi SEBELUM tanggal ini
                $isOld = SalesReport::where('phone', $phone)
                    ->where('report_date', '<', $formattedDate)
                    ->exists();

                $isOld ? $returningCount++ : $newCount++;
            }

            $newData[] = $newCount;
            $returningData[] = $returningCount;
        }

        return [
            'chart' => [
                'type' => 'area',
                'height' => 400,
                'stacked' => true,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Pelanggan Baru (Acquisition)',
                    'data' => $newData,
                ],
                [
                    'name' => 'Repeat Order (Retention)',
                    'data' => $returningData,
                ],
            ],
            'colors' => ['#3b82f6', '#8b5cf6'], // Blue & Violet
            'fill' => [
                'type' => 'gradient',
                'gradient' => ['opacityFrom' => 0.6, 'opacityTo' => 0.1],
            ],
            'xaxis' => ['categories' => $categories],
            'stroke' => ['curve' => 'smooth', 'width' => 2],
            'dataLabels' => ['enabled' => false],
            'subtitle' => [
                'text' => 'Trend pertumbuhan pelanggan baru vs loyalitas pelanggan lama',
                'align' => 'left',
                'margin' => 0,
                'offsetY' => 0,
                'style' => ['color' => '#64748b'],
            ],
        ];
    }
}