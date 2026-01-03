<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Resources\Products\Widgets\ProductHppChart;
use App\Filament\Resources\Orders\Widgets\OrderPeakTimeChart;
use App\Filament\Resources\Orders\Widgets\OrderStatsOverview;
use App\Filament\Resources\Products\Widgets\StockHealthChart;
use App\Filament\Resources\Orders\Widgets\NewOrdersTrendChart;
use App\Filament\Resources\Products\Widgets\ProductValueChart;
use App\Filament\Resources\Products\Widgets\ProductStatsOverview;
use App\Filament\Resources\SalesReports\Widgets\OrderStatusDonut;
use App\Filament\Resources\SalesReports\Widgets\PaymentMethodDonut;
use App\Filament\Resources\StockMovements\Widgets\TopActiveSkuChart;
use App\Filament\Resources\StockMovements\Widgets\StockMovementStats;
use App\Filament\Resources\ProductPackages\Widgets\PackageMarginChart;
use App\Filament\Resources\StockMovements\Widgets\MovementReasonChart;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageStats;
use App\Filament\Resources\SalesReports\Widgets\RevenueProfitTrendChart;
use App\Filament\Resources\SalesReports\Widgets\SalesReportStatsOverview;
use App\Filament\Resources\ProductPackages\Widgets\ProductPriceRangeChart;
use App\Filament\Resources\ProductPackages\Widgets\PackageProfitNominalChart;

class Dashboard extends BaseDashboard
{
  protected static ?string $title = 'Dashboard StockkuApp';
  public function getWidgets(): array
  {
    return [
      ProductStatsOverview::class,
      StockHealthChart::class,
      ProductValueChart::class,
      ProductHppChart::class,

      ProductPackageStats::class,
      PackageMarginChart::class,
      PackageProfitNominalChart::class,
      ProductPriceRangeChart::class,

      OrderStatsOverview::class,
      NewOrdersTrendChart::class,
      OrderPeakTimeChart::class,

      SalesReportStatsOverview::class,
      RevenueProfitTrendChart::class,
      PaymentMethodDonut::class,
      OrderStatusDonut::class,

      StockMovementStats::class,
      MovementReasonChart::class,
      TopActiveSkuChart::class,
    ];
  }
}
