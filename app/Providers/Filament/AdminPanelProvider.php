<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Settings\Pages\ReceiptSettings;
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
        try {
            $settings = Settings::current();
        } catch (\Exception $e) {
            $settings = null;
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')

            ->brandLogo(fn () => view('filament.components.brand'))
            ->brandLogoHeight('3rem')

            ->maxContentWidth('full')
            ->sidebarWidth('260px')
            ->sidebarCollapsibleOnDesktop()
            ->spa()

            ->colors([
                'primary' => Color::hex('#16a34a'),
            ])
            ->darkMode(false)
            ->font('Inter')

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
           ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => '
                    <link rel="stylesheet" href="' . asset('css/admin-panel.css') . '?v=' . filemtime(public_path('css/admin-panel.css')) . '">
                    <script defer src="' . asset('js/admin-panel.js') . '?v=' . filemtime(public_path('js/admin-panel.js')) . '"></script>
                '
            )
            ->login()

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
                ReceiptSettings::class,
            ])

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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