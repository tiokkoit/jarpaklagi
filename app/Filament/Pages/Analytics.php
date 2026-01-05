<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use App\Filament\Resources\Products\Widgets\AbcParetoChart;
use App\Filament\Resources\Products\Widgets\StockLevelChart;
use App\Filament\Resources\SalesReports\Widgets\ProductParetoChart;
use App\Filament\Resources\SalesReports\Widgets\CustomerRetentionChart;
use App\Filament\Resources\SalesReports\Widgets\ProductPerformanceChart;
use App\Filament\Resources\ProductPackages\Widgets\PackageBottleneckChart;
use App\Filament\Resources\SalesReports\Widgets\ProvinceDistributionChart;
use App\Filament\Resources\StockMovements\Widgets\StockMovementTrendChart;
use App\Filament\Resources\SalesReports\Widgets\GeographicDistributionChart;
use App\Filament\Resources\ProductPackages\Widgets\PackageCostStructureChart;

class Analytics extends Page
{
  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';

  protected string $view = 'filament.pages.analytics';

  protected static ?string $navigationLabel = 'Analytics';

  protected static ?string $title = 'Dashboard Analitik Bisnis';

    protected static ?int $navigationSort = 10;

    /**
     * Mengatur jumlah kolom grid agar layout lebih dinamis.
     * Kita gunakan grid 2 kolom untuk layar besar (lg).
     */
    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'default' => 1,
            'lg' => 2,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // --- 📦 GROUP 1: INVENTORY & OPS (Fokus IE) ---
            AbcParetoChart::class,          // Penting untuk prioritas stok
            StockLevelChart::class,         // Monitor level gudang
            PackageBottleneckChart::class,  // Analisis hambatan produksi
            StockMovementTrendChart::class, // Tren arus barang

            // --- 💰 GROUP 2: SALES & PERFORMANCE ---
            ProductPerformanceChart::class, 
            ProductParetoChart::class,      // Analisis 80/20 penjualan
            PackageCostStructureChart::class, // Analisis margin/biaya
            CustomerRetentionChart::class,

            // --- 🗺️ GROUP 3: MARKET DISTRIBUTION ---
            ProvinceDistributionChart::class,
            GeographicDistributionChart::class,
        ];
    }
}
