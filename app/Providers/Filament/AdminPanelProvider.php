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

// Widgets - Charts (New)
use App\Filament\Resources\Orders\Widgets\OrdersTrendsChart;
use App\Filament\Resources\SalesReports\Widgets\RevenueAnalyticsChart;
use App\Filament\Resources\ProductPackages\Widgets\ProductPriceRangeChart;
use App\Filament\Resources\StockMovements\Widgets\StockActivityChart;

// New Dashboard Widgets
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\TopSellingProductsChart;
use App\Filament\Widgets\ProfitLossOverview;
use App\Filament\Widgets\CustomerGeographyChart;

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
            ->breadcrumbs(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                    // Row 1: Global Strategic Overview
                DashboardStatsOverview::class,
                ProfitLossOverview::class, // New Financial Intel

                    // Row 2: Financial & Activity Trends
                RevenueAnalyticsChart::class,
                OrdersTrendsChart::class,

                    // Row 3: Product & Customer Intelligence
                TopSellingProductsChart::class,
                CustomerGeographyChart::class, // New Customer Intel

                    // Row 4: Detailed Resource Stats
                OrderStatsOverview::class,
                SalesReportStatsOverview::class,
                StockMovementStats::class,
                ProductStatsOverview::class,
                ProductPackageStats::class,

                    // Row 5: Granular/Specific Charts
                OrderStatusChart::class,
                SalesRevenueChart::class,
                PaymentMethodChart::class,
                StockActivityChart::class,
                ProductPriceRangeChart::class,
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
