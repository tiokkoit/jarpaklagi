<?php

namespace App\Filament\Resources\StockMovements\Widgets;

use App\Models\Product;
use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockMovementStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = now()->month;

        // --- 1. DAILY MOVEMENT (OPERASIONAL) ---
        $inToday = StockMovement::where('type', 'in')->whereDate('created_at', $today)->sum('quantity');
        $outToday = StockMovement::where('type', 'out')->whereDate('created_at', $today)->sum('quantity');

        // --- 2. INVENTORY VELOCITY (PRODUK TERAKTIF) ---
        $mostActive = StockMovement::whereDate('created_at', $today)
            ->selectRaw('product_id, count(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->with('product')
            ->first();
        $activeProductName = $mostActive ? $mostActive->product->name : 'Belum ada aktivitas';

        // --- 3. STOCK SHRINKAGE & LOSS (FINANSIAL - BULAN INI) ---
        $lossReasons = ['damaged', 'lost', 'expired'];
        $lossData = DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->whereIn('stock_movements.reason', $lossReasons)
            ->where('stock_movements.type', 'out')
            ->whereMonth('stock_movements.created_at', $thisMonth)
            ->select(
                DB::raw('SUM(stock_movements.quantity * products.hpp) as total_loss'), 
                DB::raw('SUM(stock_movements.quantity) as total_qty')
            )
            ->first();

        $totalLossValue = $lossData->total_loss ?? 0;
        $totalLossQty = $lossData->total_qty ?? 0;

        // --- 4. QUALITY CONTROL (RETURNS CUSTOMER) ---
        $returnsQty = StockMovement::where('reason', 'return_from_order')
            ->where('type', 'in')
            ->whereMonth('created_at', $thisMonth)
            ->sum('quantity');

        // --- 5. INVENTORY RECORD ACCURACY (IRA) ---
        $totalMovements = StockMovement::whereMonth('created_at', $thisMonth)->count();
        $adjustmentMovements = StockMovement::whereMonth('created_at', $thisMonth)
            ->whereIn('reason', ['adjustment_in', 'adjustment_out'])
            ->count();

        $accuracyRate = $totalMovements > 0 
            ? round((1 - ($adjustmentMovements / $totalMovements)) * 100, 1) 
            : 100;

        return [
            // KARTU 1: BARANG MASUK
            Stat::make('Arus Masuk (Hari Ini)', '+' . $inToday . ' Unit')
                ->description('Total stok masuk gudang')
                ->descriptionIcon('heroicon-s-arrow-down-on-square-stack')
                ->color('success')
                ->chart([2, 10, 5, 20, $inToday])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-emerald-500 shadow-sm hover:shadow-emerald-500/50',
                ]),

            // KARTU 2: BARANG KELUAR
            Stat::make('Arus Keluar (Hari Ini)', '-' . $outToday . ' Unit')
                ->description('Total stok keluar gudang')
                ->descriptionIcon('heroicon-s-arrow-up-on-square-stack')
                ->color('danger')
                ->chart([5, 8, 12, 15, $outToday])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-rose-500 shadow-sm hover:shadow-rose-500/50',
                ]),

            // KARTU 3: PRODUK TERAKTIF
            Stat::make('Produk Teraktif', $activeProductName)
                ->description('Produk dengan mutasi terbanyak hari ini')
                ->descriptionIcon('heroicon-s-fire')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-amber-500 shadow-sm hover:shadow-amber-500/50',
                ]),

            // KARTU 4: SHRINKAGE VALUE
            Stat::make('Penyusutan Aset (Bulan Ini)', 'Rp ' . number_format($totalLossValue, 0, ',', '.'))
                ->description($totalLossQty . ' item Rusak/Hilang/Expired')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($totalLossValue > 0 ? 'danger' : 'gray')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-red-600 shadow-sm hover:shadow-red-600/50',
                ]),

            // KARTU 5: CUSTOMER RETURNS
            Stat::make('Retur Pelanggan (QC)', $returnsQty . ' Unit')
                ->description('Barang ditarik kembali bulan ini')
                ->descriptionIcon('heroicon-m-arrow-path-rounded-square')
                ->color($returnsQty > 0 ? 'warning' : 'success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-orange-500 shadow-sm hover:shadow-orange-500/50',
                ]),

            // KARTU 6: INVENTORY RECORD ACCURACY
            Stat::make('Akurasi Catatan Inventaris', $accuracyRate . '%')
                ->description('Tingkat akurasi catatan stok bulan ini')
                ->descriptionIcon('heroicon-s-receipt-percent')
                ->color($accuracyRate >= 95 ? 'success' : ($accuracyRate >= 85 ? 'warning' : 'danger'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-blue-500 shadow-sm hover:shadow-blue-500/50',
                ]),
        ];
    }
}