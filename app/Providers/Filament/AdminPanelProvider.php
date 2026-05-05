<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetLocale;
use App\Livewire\LowStockAlert;
use App\Livewire\OrdersChart;
use App\Livewire\RecentOrders;
use App\Livewire\SalesChart;
use App\Livewire\StatsOverview;
use App\Models\Settings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $settings = Settings::current();

        $logoUrl = $settings->logo ? asset('storage/' . $settings->logo) : null;
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->brandLogo(fn () => view('filament.components.brand'))
            ->brandName($settings->CompanyName ?? config('app.name'))
            ->when($logoUrl, fn (Panel $panel) => 
                $panel->brandLogo($logoUrl)
            )
            ->brandLogoHeight('4.5rem')
            ->maxContentWidth('full')
            ->renderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn () => view('filament.components.fullscreen-button')
            )
            ->renderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn () => Settings::current()->show_language
                ? \Livewire\Livewire::mount('language-switcher')
                : null
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn () => view('filament.components.footer')
            )
            ->login()
            ->colors([
               'primary' => Color::hex('#16a34a'), 
            ])
            ->sidebarWidth('220px')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                StatsOverview::class,
                OrdersChart::class,
                SalesChart::class,
                RecentOrders::class,
                LowStockAlert::class,
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
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
