<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use Carbon\Carbon;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\CarbonPeriod;

class StockMovementTrendChart extends ApexChartWidget
{
    protected static ?string $chartId = 'movementTrendChart';
    protected static ?string $heading = 'Neraca Arus Barang (In vs Out)';
    protected int | string | array $columnSpan = 'full';

    // Default filter diset ke Minggu Ini
    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini (Senin-Minggu)',
            'last_7_days' => '7 Hari Terakhir',
            'month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
            'year' => 'Tahun Ini (Per Bulan)',
            'last_year' => 'Tahun Lalu',
        ];
    }

    protected function getOptions(): array
    {
        $activeFilter = $this->filter;
        $now = now();

        // 1. LOGIKA RANGE TANGGAL
        $startDate = match ($activeFilter) {
            'today' => $now->copy()->startOfDay(),
            'week' => $now->copy()->startOfWeek(),
            'last_7_days' => $now->copy()->subDays(6)->startOfDay(),
            'month' => $now->copy()->startOfMonth(),
            'last_month' => $now->copy()->subMonth()->startOfMonth(),
            'year' => $now->copy()->startOfYear(),
            'last_year' => $now->copy()->subYear()->startOfYear(),
            default => $now->copy()->startOfWeek(),
        };

        $endDate = match ($activeFilter) {
            'last_month' => $now->copy()->subMonth()->endOfMonth(),
            'last_year' => $now->copy()->subYear()->endOfYear(),
            default => $now->copy()->endOfDay(),
        };

        // 2. DETEKSI MODE AGREGASI
        $isYearly = in_array($activeFilter, ['year', 'last_year']);
        $groupByRaw = $isYearly ? "DATE_FORMAT(created_at, '%Y-%m')" : "DATE(created_at)";

        // 3. QUERY DATABASE
        $rawData = StockMovement::select(
                DB::raw("$groupByRaw as period"),
                DB::raw("SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as total_out")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->get()
            ->keyBy('period');

        $categories = [];
        $inData = [];
        $outData = [];

        if ($isYearly) {
            for ($i = 0; $i < 12; $i++) {
                $date = $startDate->copy()->addMonths($i);
                $key = $date->format('Y-m');
                $categories[] = $date->translatedFormat('F');
                
                $inValue = $rawData->has($key) ? $rawData[$key]->total_in : 0;
                $outValue = $rawData->has($key) ? $rawData[$key]->total_out : 0;

                $inData[] = (int) $inValue;
                $outData[] = (int) $outValue * -1; // Grafik Out ke bawah
            }
        } else {
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                $categories[] = $date->translatedFormat('d M');
                
                $inValue = $rawData->has($key) ? $rawData[$key]->total_in : 0;
                $outValue = $rawData->has($key) ? $rawData[$key]->total_out : 0;

                $inData[] = (int) $inValue;
                $outData[] = (int) $outValue * -1;
            }
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 450,
                'stacked' => true,
                'toolbar' => ['show' => false],
                'animations' => ['enabled' => true],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 5,
                    'columnWidth' => '70%',
                    'colors' => [
                        'ranges' => [
                            ['from' => -9999999, 'to' => -1, 'color' => '#f43f5e'], // Merah (Out)
                            ['from' => 0, 'to' => 9999999, 'color' => '#10b981'],   // Hijau (In)
                        ],
                    ],
                ],
            ],
            'series' => [
                ['name' => 'Barang Masuk (In)', 'data' => $inData],
                ['name' => 'Barang Keluar (Out)', 'data' => $outData],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => ['style' => ['fontWeight' => 600]],
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Volume (Unit)',
                    'style' => ['fontWeight' => 600]
                ],
            ],
            'grid' => [
                'borderColor' => '#f1f5f9',
                'yaxis' => ['lines' => ['show' => true]],
            ],
            'legend' => [
                'position' => 'top',
                'fontWeight' => 600,
            ],
            // Tanpa Formatter: Tooltip akan menampilkan angka murni
            'tooltip' => [
                'enabled' => true,
                'shared' => true,
                'intersect' => false,
                'theme' => 'light',
            ],
        ];
    }
}