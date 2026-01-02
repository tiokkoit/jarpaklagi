<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\Products\Widgets\AbcParetoChart;
use BackedEnum;
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
    ];
  }
}
