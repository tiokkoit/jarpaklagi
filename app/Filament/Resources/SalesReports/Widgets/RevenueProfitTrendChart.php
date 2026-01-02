<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class RevenueProfitTrendChart extends ApexChartWidget
{
    protected static ?string $chartId = 'revenueProfitTrendChart';
    protected static ?string $heading = 'Analisis Profitabilitas: Tren Omset vs Untung';
    protected int | string | array $columnSpan = 'full';

    // Default filter diset ke 30 hari terakhir agar sinkron
    public ?string $filter = '30_days';

    // Opsi Filter Lengkap (Identik dengan Donut Chart)
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

        // 1. Logika Penentuan Range Tanggal (Sinkron dengan Donut Chart)
        [$startDate, $endDate] = match ($activeFilter) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            '7_days' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'last_month' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'all' => [
                SalesReport::min('report_date') ? Carbon::parse(SalesReport::min('report_date'))->startOfDay() : $now->copy()->subDays(29)->startOfDay(),
                $now->copy()->endOfDay()
            ],
            default => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()],
        };

        // 2. Deteksi Mode Agregasi (Bulan vs Hari)
        $isYearly = $activeFilter === 'year';
        $groupByRaw = $isYearly ? "DATE_FORMAT(report_date, '%Y-%m')" : "DATE(report_date)";

        // 3. Query Data Keuangan (Hanya status SELESAI)
        $query = SalesReport::where('sales_reports.status', 'SELESAI')
            ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id')
            ->join('products', 'product_packages.product_id', '=', 'products.id')
            ->select(
                DB::raw("$groupByRaw as period"),
                DB::raw('SUM(sales_reports.total_price) as daily_revenue'),
                DB::raw('SUM(sales_reports.total_price - (products.hpp * product_packages.pcs_per_package * sales_reports.quantity)) as daily_profit')
            );

        // Tambahkan filter tanggal jika bukan "all"
        if ($activeFilter !== 'all') {
            $query->whereBetween('report_date', [$startDate, $endDate]);
        }

        $rawData = $query->groupBy('period')->get()->keyBy('period');

        $categories = [];
        $revenueData = [];
        $profitData = [];

        if ($isYearly) {
            // Mapping per Bulan (Januari - Desember)
            for ($i = 0; $i < 12; $i++) {
                $monthDate = $startDate->copy()->addMonths($i);
                $key = $monthDate->format('Y-m');
                
                $categories[] = $monthDate->translatedFormat('F');
                $revenueData[] = $rawData->has($key) ? (float) $rawData[$key]->daily_revenue : 0;
                $profitData[] = $rawData->has($key) ? (float) $rawData[$key]->daily_profit : 0;
            }
        } else {
            // Mapping per Hari
            $period = CarbonPeriod::create($startDate, $endDate);
            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                
                $categories[] = $date->translatedFormat('d M');
                $revenueData[] = $rawData->has($key) ? (float) $rawData[$key]->daily_revenue : 0;
                $profitData[] = $rawData->has($key) ? (float) $rawData[$key]->daily_profit : 0;
            }
        }

        return [
            'chart' => [
                'type' => 'area',
                'height' => 400,
                'toolbar' => ['show' => false],
                'animations' => [
                    'enabled' => true,
                    'easing' => 'easeinout',
                    'speed' => 800,
                ],
            ],
            'series' => [
                [
                    'name' => 'Omset (Gross Revenue)',
                    'data' => $revenueData,
                ],
                [
                    'name' => 'Untung (Net Profit)',
                    'data' => $profitData,
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
                'labels' => [
                    'hideOverlappingLabels' => true,
                    'style' => ['fontWeight' => 600, 'colors' => '#64748b'],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => ['colors' => '#64748b'],
                ],
                'title' => [
                    'text' => 'Nilai Rupiah (Rp)',
                    'style' => ['fontWeight' => 600],
                ],
            ],
            'colors' => ['#6366f1', '#10b981'], // Indigo (Revenue) & Emerald (Profit)
            'stroke' => [
                'curve' => 'smooth',
                'width' => [3, 4],
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.45,
                    'opacityTo' => 0.05,
                    'stops' => [20, 100],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'grid' => [
                'borderColor' => '#f1f5f9',
                'strokeDashArray' => 4,
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'right',
            ],
            'subtitle' => [
                'text' => 'Perbandingan pendapatan dan margin keuntungan bersih produk Kelor',
                'align' => 'left',
                'style' => ['color' => '#64748b', 'fontSize' => '14px'],
            ],
            'tooltip' => [
                'theme' => 'light',
                'shared' => true,
                'intersect' => false,
            ],
        ];
    }
}