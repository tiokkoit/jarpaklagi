<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductPackage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InventoryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Stok Global: Jumlahkan kolom 'stock' dari tabel products
        $totalStock = Product::sum('stock');
        
        // Nilai Inventaris: (HPP * Stock) di tabel products
        $inventoryValue = Product::select(DB::raw('SUM(hpp * stock) as total_value'))
                                 ->value('total_value') ?? 0;

        return [
            Stat::make('Total Produk Unik', Product::count())
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
        ];
    }
}