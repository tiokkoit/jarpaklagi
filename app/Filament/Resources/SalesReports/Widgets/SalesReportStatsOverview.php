<?php

namespace App\Filament\Resources\SalesReports\Widgets;

use App\Models\SalesReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Penguncian Periode: 30 Hari Terakhir (Rolling 30 Days)
        $startDate = now()->subDays(29)->startOfDay();
        $endDate = now()->endOfDay();
        
        // Keterangan Rentang Waktu untuk Deskripsi (Contoh: 05 Des - 03 Jan)
        $dateRangeInfo = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M');

        // --- 2. DATA TRANSAKSI (30 HARI) ---
        $countSelesai = SalesReport::where('status', 'SELESAI')
            ->whereBetween('report_date', [$startDate, $endDate])->count();
            
        $countDikembalikan = SalesReport::where('status', 'DIKEMBALIKAN')
            ->whereBetween('report_date', [$startDate, $endDate])->count();
            
        $countCancel = SalesReport::where('status', 'CANCEL')
            ->whereBetween('report_date', [$startDate, $endDate])->count();

        // --- 3. DATA KEUANGAN (30 HARI) ---
        $revenue = SalesReport::where('status', 'SELESAI')
            ->whereBetween('report_date', [$startDate, $endDate])
            ->sum('total_price');

        $profit = SalesReport::where('sales_reports.status', 'SELESAI')
            ->whereBetween('report_date', [$startDate, $endDate])
            ->join('product_packages', 'sales_reports.product_package_id', '=', 'product_packages.id')
            ->join('products', 'product_packages.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(sales_reports.total_price - (products.hpp * product_packages.pcs_per_package * sales_reports.quantity)) as total_profit'))
            ->first()->total_profit ?? 0;

        $aov = $countSelesai > 0 ? $revenue / $countSelesai : 0;
        $margin = $revenue > 0 ? ($profit / $revenue) * 100 : 0;

        // --- 4. DATA PELANGGAN (30 HARI) ---
        $phonesInPeriod = SalesReport::whereBetween('report_date', [$startDate, $endDate])
            ->pluck('phone')->unique();

        $newCustomers = 0;
        $repeatCustomers = 0;

        foreach ($phonesInPeriod as $phone) {
            $isOld = SalesReport::where('phone', $phone)
                ->where('report_date', '<', $startDate)->exists();
            $isOld ? $repeatCustomers++ : $newCustomers++;
        }

        return [
            // BARIS 1: OPERASIONAL (Fulfillment Metrics)
            Stat::make('Pesanan SELESAI', $countSelesai . ' Trx')
                ->description("Sukses terkirim ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 10, 12, 18, 14, 20, 25])
                ->extraAttributes(['class' => 'border-b-4 border-emerald-500 shadow-md rounded-xl hover:scale-105 transition']),

            Stat::make('Pesanan DIKEMBALIKAN', $countDikembalikan . ' Trx')
                ->description("Retur/Barang kembali ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning')
                ->extraAttributes(['class' => 'border-b-4 border-amber-500 shadow-md rounded-xl hover:scale-105 transition']),

            Stat::make('Pesanan CANCEL', $countCancel . ' Trx')
                ->description("Transaksi batal ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->extraAttributes(['class' => 'border-b-4 border-rose-500 shadow-md rounded-xl hover:scale-105 transition']),

            // BARIS 2: FINANSIAL (Profitability Metrics)
            Stat::make('Total Omset', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description("Revenue masuk ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([15, 25, 20, 35, 45, 50, 60])
                ->color('primary')
                ->extraAttributes(['class' => 'border-b-4 border-blue-600 shadow-md rounded-xl hover:scale-105 transition']),

            Stat::make('Total Untung', 'Rp ' . number_format($profit, 0, ',', '.'))
                ->description("Estimasi profit bersih ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart([10, 20, 15, 30, 40, 45, 55])
                ->color('success')
                ->extraAttributes(['class' => 'border-b-4 border-teal-500 shadow-md rounded-xl hover:scale-105 transition']),

            Stat::make('Margin Profit', number_format($margin, 1) . '%')
                ->description("Rasio efisiensi untung ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-variable')
                ->color('info')
                ->extraAttributes(['class' => 'border-b-4 border-cyan-500 shadow-md rounded-xl hover:scale-105 transition']),

            // BARIS 3: PELANGGAN (Growth & Loyalty Metrics)
            Stat::make('Customer Baru', $newCustomers . ' Orang')
                ->description("Akuisisi pelanggan ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info')
                ->extraAttributes(['class' => 'border-b-4 border-sky-500 shadow-md rounded-xl hover:scale-105 transition']),

            Stat::make('Repeat Order', $repeatCustomers . ' Orang')
                ->description("Loyalitas pelanggan ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-heart')
                ->color('warning')
                ->extraAttributes(['class' => 'border-b-4 border-orange-500 shadow-md rounded-xl hover:scale-105 transition']),
            
            Stat::make('Rata-rata Belanja', 'Rp ' . number_format($aov, 0, ',', '.'))
                ->description("Nilai AOV per transaksi ($dateRangeInfo)")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->extraAttributes(['class' => 'border-b-4 border-indigo-500 shadow-md rounded-xl hover:scale-105 transition']),
        ];
    }
}