<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Stephenjude\FilamentDebugger\DebuggerPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // Filament Config
            ->default()
            ->id('app')
            ->path('/')
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->maxContentWidth(MaxWidth::Full)
            ->defaultThemeMode(ThemeMode::Light)
            // Application changes for UIS
            ->favicon(asset('/favicon.ico'))
            ->brandLogo(fn () => view('filament.logo.light'))
            ->darkModeBrandLogo(fn () => view('filament.logo.dark'))
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                'panels::body.end',
                fn () => view('footer'),
            )
            ->viteTheme('resources/css/filament/app/theme.css')
            // Filament additional configs
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->navigationGroups([
                NavigationGroup::make('Portal')->icon('heroicon-o-beaker'),
            ])
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentSpatieLaravelHealthPlugin::make()
                    ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                DebuggerPlugin::make()
                    ->navigationGroup(condition: true, label: 'Debugger')
                    ->authorize(fn () => auth()->user()->hasRole('super_admin')),
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

    public function register(): void
    {
        parent::register();
        FilamentView::registerRenderHook('panels::body.end', fn (): string => Blade::render("@vite('resources/js/app.js')"));
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('google-analytics', 'https://www.googletagmanager.com/gtag/js?id=UA-125366978-1'),
            Js::make('onetrust', 'https://cdn.cookielaw.org/consent/3ca42bb6-c1b2-4c5d-9e95-b3c10f01a06c.js'),
            Js::make('custom-script', __DIR__ . '/../../../resources/js/custom.js'),
        ]);
    }
}
