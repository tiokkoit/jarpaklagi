<?php

namespace App\Filament\Resources\ProductPackages\Widgets;

use App\Models\ProductPackage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductPackageStats extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Ambil data paket yang aktif beserta data produk induknya (Eager Loading)
        $activePackages = ProductPackage::with('product')->where('is_active', true)->get();
        
        // --- 1. PRICING HEALTH (Analitika Data) ---
        $prices = $activePackages->pluck('price')->sort()->values();
        $count = $prices->count();
        $medianPrice = 0;
        if ($count > 0) {
            $middle = floor(($count - 1) / 2);
            $medianPrice = ($count % 2) 
                ? $prices[$middle] 
                : ($prices[$middle] + $prices[$middle + 1]) / 2;
        }
        $avgPrice = $prices->avg() ?? 0;

        // --- 2. MOST PROFITABLE (Kewirausahaan) ---
        // Margin = Harga Jual - (HPP Satuan * Isi per Paket)
        $mostProfitable = $activePackages->map(function ($package) {
            $totalHpp = ($package->product->hpp ?? 0) * $package->pcs_per_package;
            return [
                'name' => $package->name,
                'margin' => $package->price - $totalHpp
            ];
        })->sortByDesc('margin')->first();

        // --- NEW: HIGHEST MARGIN PERCENTAGE ---
        $highestMarginPercentage = $activePackages->map(function ($package) {
            $totalHpp = ($package->product->hpp ?? 0) * $package->pcs_per_package;
            $margin = $package->price - $totalHpp;
            // Hindari pembagian dengan nol
            $percentage = $package->price > 0 ? ($margin / $package->price) * 100 : 0;
            
            return [
                'name' => $package->name,
                'percentage' => $percentage
            ];
        })->sortByDesc('percentage')->first();

        // --- 3. STOCK BOTTLENECK & FULFILLMENT (SCM / Riset Operasi) ---
        $fulfillmentData = $activePackages->map(function ($package) {
            // Berapa paket yang bisa dibuat dari stok produk satuan?
            $stockAvailable = $package->product->stock ?? 0;
            $canMake = $package->pcs_per_package > 0 
                ? floor($stockAvailable / $package->pcs_per_package) 
                : 0;

            return [
                'name' => $package->name,
                'qty' => $canMake
            ];
        });

        $bottleneckPackage = $fulfillmentData->sortBy('qty')->first();
        $totalFulfillmentPower = $fulfillmentData->sum('qty');

        return [
          // 1. KOLEKSI PAKET (Scope Management)
          Stat::make('Varian Paket Aktif', $activePackages->count() . ' Jenis Paket')
              ->description('Total variasi paket di etalase saat ini')
              ->descriptionIcon('heroicon-m-rectangle-group')
              ->chart([7, 3, 4, 5, 6, 3, 5, 8])
              ->color('indigo')
              ->extraAttributes([
                  'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-indigo-500 shadow-lg hover:shadow-indigo-500/50',
              ]),

          // 2. PRICING HEALTH (Analitik Distribusi)
          Stat::make('Keseimbangan Harga', 'Average: Rp ' . number_format($avgPrice, 0, ',', '.'))
              ->description('Median: Rp ' . number_format($medianPrice, 0, ',', '.'))
              ->descriptionIcon('heroicon-m-scale')
              ->chart([10, 12, 11, 13, 12, 14, 13, 15])
              ->color('info')
              ->extraAttributes([
                  'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-cyan-500 shadow-lg hover:shadow-cyan-500/50',
              ]),

          // 3. HIGHEST MARGIN (Profit Optimization)
          Stat::make('Margin Paket Tertinggi', 'Rp ' . number_format($mostProfitable['margin'] ?? 0, 0, ',', '.'))
              ->description('Oleh: ' . ($mostProfitable['name'] ?? '-'))
              ->descriptionIcon('heroicon-m-arrow-trending-up')
              ->chart([2, 5, 10, 15, 25, 22, 30, 40])
              ->color('success')
              ->extraAttributes([
                  'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-emerald-500 shadow-lg hover:shadow-emerald-500/50',
              ]),

          // 4. NEW: HIGHEST MARGIN PERCENTAGE (Efficiency)
          Stat::make('Persentase Margin Terbesar', number_format($highestMarginPercentage['percentage'] ?? 0, 1, ',', '.') . '%')
              ->description('Produk: ' . ($highestMarginPercentage['name'] ?? '-'))
              ->descriptionIcon('heroicon-m-presentation-chart-line')
              ->chart([15, 25, 20, 30, 45, 40, 50, 60])
              ->color('primary')
              ->extraAttributes([
                  'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-blue-500 shadow-lg hover:shadow-blue-500/50',
              ]),

          // 5. BOTTLENECK (Production Constraint)
          Stat::make('Limit Kesiapan Terendah (Bottleneck)', ($bottleneckPackage['qty'] ?? 0) . ' Paket')
              ->description('Kritis di: ' . ($bottleneckPackage['name'] ?? '-'))
              ->descriptionIcon('heroicon-m-exclamation-circle')
              ->chart([20, 18, 15, 12, 10, 8, 5, 2])
              ->color('danger')
              ->extraAttributes([
                  'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-rose-500 shadow-lg hover:shadow-rose-500/50',
              ]),

          // 6. FULFILLMENT POWER (Aggregate Capacity)
          Stat::make('Total Peluang Penjualan', number_format($totalFulfillmentPower, 0, ',', '.') . ' Pesanan')
              ->description('Kapasitas akumulasi seluruh opsi paket')
              ->descriptionIcon('heroicon-m-shopping-cart')
              ->chart([15, 20, 18, 25, 30, 28, 35, 40])
              ->color('warning')
              ->extraAttributes([
                  'class' => 'cursor-pointer hover:scale-105 transition-all duration-300 border-b-4 border-amber-500 shadow-lg hover:shadow-amber-500/50',
              ]),
      ];
    }
}