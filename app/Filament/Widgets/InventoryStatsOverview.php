<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\StockMovement; // <-- Import Model StockMovement
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InventoryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // --- DATA UTAMA ---
        
        // 1. Ambil Total Stok Global
        $totalStock = Product::sum('stock');
        
        // 2. Hitung Nilai Inventaris (HPP * Stock)
        $inventoryValue = Product::select(DB::raw('SUM(hpp * stock) as total_value'))
                                 ->value('total_value') ?? 0;
        
        // 3. Ambil Total Produk Unik
        $totalProducts = Product::count();
        
        
        // --- DATA KRITIS (TAMBAHAN) ---
        
        // 4. Hitung Produk Stok Kosong (Stok = 0)
        $outOfStockCount = Product::where('stock', 0)->count();

        // 5. Hitung Produk Stok Rendah (Misalnya, di bawah 10 unit)
        $lowStockThreshold = 10;
        $lowStockCount = Product::where('stock', '>', 0)
                                ->where('stock', '<=', $lowStockThreshold)
                                ->count();
                                
        // 6. Total Unit Stok Masuk Hari Ini (Membutuhkan model StockMovement)
        $todayStockIn = StockMovement::where('type', 'in')
                                     ->whereDate('created_at', today())
                                     ->sum('quantity');

        return [
            // BARIS 1: Fokus pada Total Inventaris & Produk
            
            Stat::make('Total Produk Unik', $totalProducts)
                ->description('Jumlah SKU produk yang terdaftar')
                ->color('primary')
                ->icon('heroicon-o-gift'),
            
            Stat::make('Total Stok Barang', number_format($totalStock))
                ->description('Total unit barang tersedia di semua produk')
                ->color($totalStock > 0 ? 'success' : 'danger')
                ->icon('heroicon-o-inbox-stack'),
                
            Stat::make('Total Nilai Inventaris', 'Rp ' . number_format($inventoryValue, 0, ',', '.'))
                ->description('Total HPP dari semua stok yang ada')
                ->color('info')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Paket Aktif', ProductPackage::where('is_active', true)->count())
                ->description('Jumlah paket penjualan yang bisa digunakan')
                ->color('warning')
                ->icon('heroicon-o-archive-box-arrow-down'),
                
            // BARIS 2: Fokus pada Kesehatan Stok & Aktivitas
            
            Stat::make('Produk Stok Kosong', $outOfStockCount)
                ->description('Jumlah SKU yang memerlukan restock segera')
                ->color($outOfStockCount > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-x-circle'),
                
            Stat::make('Produk Stok Rendah', $lowStockCount)
                ->description('Jumlah SKU di bawah ' . $lowStockThreshold . ' unit')
                ->color($lowStockCount > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),

            Stat::make('Stok Masuk Hari Ini', number_format($todayStockIn))
                ->description('Total unit barang diterima hari ini')
                ->color('success')
                ->icon('heroicon-o-arrow-turn-right-down'),
        ];
    }
}