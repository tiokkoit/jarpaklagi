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
use App\Filament\Resources\Orders\Widgets\OrderStatsOverview;
use App\Filament\Resources\StockMovements\Widgets\StockMovementStats;
use App\Filament\Resources\SalesReports\Widgets\SalesReportStatsOverview;
use App\Filament\Resources\Products\Widgets\ProductStatsOverview;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageStats;
use App\Filament\Resources\Orders\Widgets\OrderStatusChart;
use App\Filament\Resources\StockMovements\Widgets\StockMovementTrendChart;
use App\Filament\Resources\SalesReports\Widgets\SalesRevenueChart;
use App\Filament\Resources\SalesReports\Widgets\PaymentMethodChart;
use App\Filament\Resources\Products\Widgets\ProductStockChart;
use App\Filament\Resources\Products\Widgets\ProductValueChart;
use App\Filament\Resources\ProductPackages\Widgets\ProductPackageActiveChart;
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
            ->profile() // Default profile for name, email, password
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
                OrderStatsOverview::class,
                StockMovementStats::class,
                SalesReportStatsOverview::class,
                ProductStatsOverview::class,
                ProductPackageStats::class,
                OrderStatusChart::class,
                StockMovementTrendChart::class,
                SalesRevenueChart::class,
                PaymentMethodChart::class,
                ProductStockChart::class,
                ProductValueChart::class,
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
