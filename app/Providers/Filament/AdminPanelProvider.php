<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Widgets - Stats Overview
use App\Filament\Resources\Orders\Widgets\OrderStatsOverview;
use App\Filament\Resources\SalesReports\Widgets\SalesReportStatsOverview;
use App\Filament\Resources\StockMovements\Widgets\StockMovementStats;
use App\Filament\Resources\Products\Widgets\ProductStatsOverview;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageStats;

// Widgets - Charts (Existing)
use App\Filament\Resources\Orders\Widgets\OrderStatusChart;
use App\Filament\Resources\SalesReports\Widgets\SalesRevenueChart;
use App\Filament\Resources\SalesReports\Widgets\PaymentMethodChart;
use App\Filament\Resources\Products\Widgets\ProductStockChart;
use App\Filament\Resources\Products\Widgets\ProductValueChart;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageActiveChart;

// Widgets - Charts (New)
use App\Filament\Resources\Orders\Widgets\OrdersTrendsChart;
use App\Filament\Resources\SalesReports\Widgets\RevenueAnalyticsChart;
use App\Filament\Resources\ProductPackages\Widgets\ProductPriceRangeChart;
use App\Filament\Resources\StockMovements\Widgets\StockActivityChart;

use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->colors([
                'primary' => Color::Emerald,
                'teal' => Color::Teal,
                'purple' => Color::Purple,
            ])
            ->font('Poppins')
            ->brandName('StockkuApp')
            ->brandLogo('/images/stockku-logo.png')
            ->favicon('/images/stockku-favicon.png')
            ->renderHook(
                'panels::head.end',
                fn(): string => request()->routeIs('filament.admin.auth.login')
                ? '<link rel="stylesheet" href="' . asset('css/filament-login.css') . '" />' .
                '<script>document.documentElement.classList.add("dark");</script>'
                : ''
            )
            ->brandLogoHeight(fn() => auth()->user() ? '3rem' : '8rem')
            ->topNavigation()
            ->spa()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                    // Row 1: Global Stats
                OrderStatsOverview::class,
                SalesReportStatsOverview::class,

                    // Row 2: Secondary Stats
                StockMovementStats::class,
                ProductStatsOverview::class,
                ProductPackageStats::class,

                    // Row 3: Primary Trends (Full Width or Large)
                OrdersTrendsChart::class,       // New
                RevenueAnalyticsChart::class,   // New

                    // Row 4: Detailed Analysis
                OrderStatusChart::class,
                SalesRevenueChart::class, // Keep existing revenue chart as valid alternative or remove if redundant? Keeping for now as it has different view (90 days vs 30 days mixed)
                PaymentMethodChart::class,

                    // Row 5: Stock & Product Analysis
                StockActivityChart::class,      // New
                ProductStockChart::class,
                ProductValueChart::class,
                ProductPriceRangeChart::class,  // New
                ProductPackageActiveChart::class,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make()
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
