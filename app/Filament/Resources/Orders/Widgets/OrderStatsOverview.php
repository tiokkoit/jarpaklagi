<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $totalOrders = Order::count();

        // --- 1. CURRENT WORKLOAD (REAL-TIME) ---
        $newOrders = Order::where('status', Order::STATUS_NEW)->count();
        $sentOrders = Order::where('status', Order::STATUS_DIKIRIM)->count();
        $completedToday = Order::where('status', Order::STATUS_SELESAI)
            ->whereDate('updated_at', $today)
            ->count();

        // --- 2. BOTTLENECK DETECTION (TI LOGIC) ---
        // Pesanan NEW yang sudah masuk lebih dari 24 jam tapi belum diproses
        $overdueOrders = Order::where('status', Order::STATUS_NEW)
            ->where('created_at', '<=', now()->subHours(24))
            ->count();

        // --- 3. PROCESS EFFICIENCY (ALL TIME) ---
        // Rata-rata waktu proses (Created -> Shipped/Completed) dalam satuan jam
        $avgLeadTime = Order::whereIn('status', [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
            ->first()->avg_hours ?? 0;

        $successCount = Order::where('status', Order::STATUS_SELESAI)->count();
        $successRate = $totalOrders > 0 ? ($successCount / $totalOrders) * 100 : 0;

        $returnedCount = Order::where('status', Order::STATUS_DIKEMBALIKAN)->count();
        $returnRate = $totalOrders > 0 ? ($returnedCount / $totalOrders) * 100 : 0;

        return [
            // BARIS 1: OPERASIONAL HARIAN
            Stat::make('Pesanan Baru', $newOrders . ' Order')
                ->description('Backlog yang perlu di-packing')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info')
                ->extraAttributes(['class' => 'border-b-4 border-amber-500 shadow-sm']),

            Stat::make('Antrean Overdue', $overdueOrders . ' Order')
                ->description('> 24 Jam belum diproses')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdueOrders > 0 ? 'danger' : 'success')
                ->extraAttributes(['class' => 'border-b-4 border-red-600 shadow-sm']),

            Stat::make('Sedang Dikirim', $sentOrders . ' Order')
                ->description('Dalam penanganan kurir')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning')
                ->extraAttributes(['class' => 'border-b-4 border-blue-500 shadow-sm']),

            Stat::make('Selesai Hari Ini', $completedToday . ' Selesai')
                ->description('Pesanan selesai hari ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->extraAttributes(['class' => 'border-b-4 border-emerald-500 shadow-sm']),

            // BARIS 2: ANALISIS PERFORMA (TI FOCUS)
            Stat::make('Avg. Lead Time', round($avgLeadTime, 1) . ' Jam')
                ->description('Rata-rata kecepatan proses')
                ->descriptionIcon('heroicon-m-bolt')
                ->color($avgLeadTime > 24 ? 'warning' : 'success')
                ->extraAttributes(['class' => 'border-b-4 border-indigo-500 shadow-sm']),

            Stat::make('Success Rate', number_format($successRate, 1) . '%')
                ->description('Reliabilitas sistem fulfillment')
                ->descriptionIcon('heroicon-m-hand-thumb-up')
                ->color('success')
                ->extraAttributes(['class' => 'border-b-4 border-teal-500 shadow-sm']),

            Stat::make('Return Rate', number_format($returnRate, 1) . '%')
                ->description($returnedCount . ' Pesanan bermasalah/retur')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning')
                ->extraAttributes(['class' => 'border-b-4 border-rose-500 shadow-sm']),
        ];
    }
}