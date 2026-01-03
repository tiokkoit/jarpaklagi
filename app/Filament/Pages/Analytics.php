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

  protected static ?string $title = 'Business Analytics';

  protected static ?int $navigationSort = 10;

  protected function getHeaderWidgets(): array
  {
    return [
      AbcParetoChart::class,
      StockLevelChart::class,
      PackageBottleneckChart::class,
      PackageCostStructureChart::class,
      StockMovementTrendChart::class,
      ProductPerformanceChart::class,
      ProductParetoChart::class,
      CustomerRetentionChart::class,
      ProvinceDistributionChart::class,
      GeographicDistributionChart::class,
    ];
  }
}
