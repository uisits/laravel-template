<?php

namespace App\Providers\Filament;

use App\Models\Semester;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use HusamTariq\FilamentDatabaseSchedule\FilamentDatabaseSchedulePlugin;
use Filament\Enums\ThemeMode;
use Orion\FilamentGreeter\GreeterPlugin;

class AppPanelProvider extends PanelProvider
{
    /**
     * @throws \Exception
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->darkMode()
            ->id('app')
            ->path('/')
            ->colors([
                'primary' => Color::Indigo,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.components.footer'),
            )
            ->favicon(asset('/favicon.ico'))
            ->brandLogoHeight('40px')
            ->brandLogo(fn () => view('filament.logo.light'))
            ->darkModeBrandLogo(fn () => view('filament.logo.dark'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('MLS Portal')->icon('heroicon-o-beaker'),
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
                FilamentDatabaseSchedulePlugin::make(),
                FilamentShieldPlugin::make(),
                GreeterPlugin::make()
                    ->message('Welcome to')
                    ->name('UIS Course Survey for ' . Semester::where('active', true)->firstOrFail()->semester_description)
                    ->title('These anonymous surveys are considered when evaluating an instructor’s job performance and are meant to provide feedback to help instructors improve their teaching. Instructors will not receive feedback until final course grades have posted. Additionally, UIS recognizes that course evaluations are sometimes influenced by unintentional biases regarding the race and/or gender of the instructor (Peterson et al., 2019). As you complete this course survey, please provide honest and constructive comments where indicated on the content of the course (e.g. assignments, content, delivery, etc.) and not unrelated matters (i.e. the instrutors appearance.')
                    ->sort(1)
                    ->columnSpan('full'),
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

    /**
     * Registers the application.
     *
     * @return void
     * @throws \Exception
     */
    public function register(): void
    {
        parent::register();
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn(): string => Blade::render("@vite('resources/js/app.js')")
        );
    }
}
