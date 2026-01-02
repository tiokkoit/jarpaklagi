<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProductStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // 1. Hitung total duit yang "nyangkut" di stok barang
        $totalInventoryValue = Product::query()
            ->select(DB::raw('SUM(hpp * stock) as total_value'))
            ->first()->total_value ?? 0;

        // 2. Hitung jumlah total unit fisik
        $totalStockUnits = Product::sum('stock') ?? 0;

        return [
            // --- BARIS ATAS: OVERVIEW ASET ---
            
            Stat::make('Valuasi Aset Barang', 'Rp ' . number_format($totalInventoryValue, 0, ',', '.'))
                ->description('Total modal yang jadi stok di gudang')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([7, 10, 8, 12, 15, 14, 18])
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-emerald-500 shadow-lg hover:shadow-emerald-500/50',
                ]),

            Stat::make('Isi Gudang Sekarang', number_format($totalStockUnits, 0, ',', '.') . ' Pcs')
                ->description('Gabungan semua unit barang yang ada')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->chart([15, 12, 17, 14, 20, 18, 22])
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-cyan-500 shadow-lg hover:shadow-cyan-500/50',
                ]),

            Stat::make('Koleksi Varian', Product::count() . ' Produk')
                ->description('Banyaknya jenis SKU aktif')
                ->descriptionIcon('heroicon-m-cube')
                ->chart([5, 5, 5, 5, 5, 5, 5])
                ->color('indigo')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-indigo-500 shadow-lg hover:shadow-indigo-500/50',
                ]),

            // --- BARIS BAWAH: STATUS OPERASIONAL ---

            Stat::make('Stok Aman Terkendali', Product::where('stock', '>', 400)->count() . ' Produk')
                ->description('Barang melimpah (> 400 unit)')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([10, 15, 12, 18, 20, 22, 25])
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-green-500 shadow-lg hover:shadow-green-500/50',
                ]),

            Stat::make('Waspada, Mulai Menipis', Product::whereBetween('stock', [100, 400])->count() . ' Produk')
                ->description('Barang sisa 100 - 400 unit')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([10, 8, 12, 7, 10, 5, 9])
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-amber-500 shadow-lg hover:shadow-amber-500/50',
                ]),

            Stat::make('Gawat! Hampir Habis', Product::where('stock', '>', 0)->where('stock', '<', 100)->count() . ' Produk')
                ->description('Segera restock (< 100 unit)')
                ->descriptionIcon('heroicon-m-fire')
                ->chart([20, 15, 10, 15, 20, 25, 30])
                ->color('danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-rose-500 shadow-lg hover:shadow-rose-500/50',
                ]),
        ];
    }
}
