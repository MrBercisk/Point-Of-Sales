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
        $settings = Settings::current();

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
                <style>
                    /* ── Sidebar ── */
                    .fi-sidebar {
                        background: #ffffff !important;
                        border-right: 1px solid #f0f0f0 !important;
                        box-shadow: 2px 0 20px rgba(0,0,0,0.05) !important;
                    }

                    /* ── Semua nav item — reset dulu ── */
                    .fi-sidebar-nav .fi-sidebar-item a,
                    .fi-sidebar-nav .fi-sidebar-item button {
                        border-radius: 10px !important;
                        padding: 0.3rem 0.9rem !important;
                        font-size: 0.875rem !important;
                        font-weight: 500 !important;
                        color: #374151 !important;
                        transition: all 0.15s ease !important;
                        text-decoration: none !important;
                        display: flex !important;
                        align-items: center !important;
                        gap: 0.6rem !important;
                        margin: 1px 0 !important;
                    }

                    /* ── Paksa semua teks & span di dalam nav item ── */
                    .fi-sidebar-nav .fi-sidebar-item a *,
                    .fi-sidebar-nav .fi-sidebar-item button * {
                        color: inherit !important;
                    }

                    /* ── Hover ── */
                    .fi-sidebar-nav .fi-sidebar-item a:hover,
                    .fi-sidebar-nav .fi-sidebar-item button:hover {
                        background: #f0fdf4 !important;
                        color: #15803d !important;
                    }
                    .fi-sidebar-nav .fi-sidebar-item a:hover svg,
                    .fi-sidebar-nav .fi-sidebar-item button:hover svg {
                        color: #16a34a !important;
                    }

                    /* ── Active ── */
                    .fi-sidebar-nav .fi-sidebar-item a[aria-current],
                    .fi-sidebar-nav .fi-sidebar-item a[aria-current="page"],
                    .fi-sidebar-nav .fi-sidebar-item a.fi-active,
                    .fi-sidebar-nav .fi-sidebar-item button.fi-active,
                    .fi-active .fi-sidebar-item-button,
                    .fi-sidebar-item.fi-active > a,
                    .fi-sidebar-item.fi-active button,
                    [class*="fi-sidebar-item"] [aria-current="page"] {
                        background: #16a34a !important;
                        color: #ffffff !important;
                        font-weight: 600 !important;
                        box-shadow: 0 4px 14px rgba(22,163,74,0.35) !important;
                    }

                    /* ── Paksa semua child elemen active ikut putih ── */
                    .fi-sidebar-nav .fi-sidebar-item a[aria-current] *,
                    .fi-sidebar-nav .fi-sidebar-item a[aria-current="page"] *,
                    .fi-sidebar-nav .fi-sidebar-item a.fi-active *,
                    .fi-sidebar-nav .fi-sidebar-item button.fi-active *,
                    .fi-sidebar-item.fi-active > a *,
                    .fi-sidebar-item.fi-active button *,
                    [class*="fi-sidebar-item"] [aria-current="page"] * {
                        color: #ffffff !important;
                    }

                    /* ── Khusus SVG active ── */
                    .fi-sidebar-nav .fi-sidebar-item a[aria-current] svg,
                    .fi-sidebar-nav .fi-sidebar-item a[aria-current="page"] svg,
                    .fi-sidebar-nav .fi-sidebar-item a.fi-active svg,
                    .fi-sidebar-nav .fi-sidebar-item button.fi-active svg,
                    .fi-sidebar-item.fi-active > a svg,
                    .fi-sidebar-item.fi-active button svg {
                        color: #ffffff !important;
                        fill: none !important;
                        stroke: #ffffff !important;
                    }
                        

                    /* ── Badge angka ── */
                    .fi-sidebar-item .fi-badge {
                        font-size: 0.65rem !important;
                        font-weight: 700 !important;
                        padding: 1px 7px !important;
                        border-radius: 999px !important;
                        min-width: 20px !important;
                        text-align: center !important;
                    }
                    .fi-sidebar-group {
                        margin-top: 0 !important;
                        padding-top: 0 !important;
                        gap: 0 !important;
                    }
                    .fi-sidebar-group-items,
                    .fi-sidebar-nav > ul,
                    .fi-sidebar-nav > div {
                        gap: 0 !important;
                        row-gap: 0 !important;
                    }

                    /* ── Group label ── */
                    .fi-sidebar-group-label,
                    [class*="fi-sidebar-group"] > div > span,
                    .fi-sidebar-group > div:first-child span {
                        font-size: 0.67rem !important;
                        font-weight: 700 !important;
                        letter-spacing: 0.09em !important;
                        text-transform: uppercase !important;
                        color: #9ca3af !important;
                        padding-top: 0.8rem !important;
                        padding-bottom: 0.25rem !important;
                        display: block !important;
                    }

                    /* ── Sidebar collapse btn — pill ── */
                    .fi-sidebar-collapse-btn {
                        width: auto !important;
                        height: 26px !important;
                        padding: 0 10px !important;
                        border-radius: 999px !important;
                        background: #f3f4f6 !important;
                        border: 1px solid #e5e7eb !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        color: #6b7280 !important;
                        transition: all 0.15s ease !important;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.06) !important;
                    }
                    .fi-sidebar-collapse-btn:hover {
                        background: #f0fdf4 !important;
                        border-color: #16a34a !important;
                        color: #16a34a !important;
                        box-shadow: 0 1px 6px rgba(22,163,74,0.2) !important;
                    }
                    .fi-sidebar-collapse-btn svg { display: none !important; }
                    .fi-sidebar-collapse-btn::before {
                        content: "" !important;
                        display: inline-block !important;
                        width: 14px !important;
                        height: 14px !important;
                        background-color: currentColor !important;
                        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpath d=\'M15 18l-6-6 6-6\'/%3E%3C/svg%3E") !important;
                        mask-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpath d=\'M15 18l-6-6 6-6\'/%3E%3C/svg%3E") !important;
                        -webkit-mask-size: contain !important;
                        mask-size: contain !important;
                        -webkit-mask-repeat: no-repeat !important;
                        mask-repeat: no-repeat !important;
                        -webkit-mask-position: center !important;
                        mask-position: center !important;
                        flex-shrink: 0 !important;
                    }

                    /* ── Main content background ── */
                    .fi-main { background: #f8fafc !important; }

                    /* ── Topbar ── */
                    .fi-topbar {
                        border-bottom: 1px solid #f0f0f0 !important;
                        box-shadow: 0 1px 8px rgba(0,0,0,0.04) !important;
                    }

                    /* ── Scrollbar ── */
                    .fi-sidebar-nav::-webkit-scrollbar { width: 3px !important; }
                    .fi-sidebar-nav::-webkit-scrollbar-thumb {
                        background: #bbf7d0 !important;
                        border-radius: 99px !important;
                    }
                </style>'
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