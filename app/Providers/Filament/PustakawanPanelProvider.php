<?php

namespace App\Providers\Filament;

use App\Filament\Pustakawan\Resources\AnggotaResource;
use App\Filament\Pustakawan\Resources\BukuResource;
use App\Filament\Pustakawan\Resources\PeminjamanResource;
use App\Filament\Pustakawan\Resources\PengembalianResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PustakawanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pustakawan')
            ->path('pustakawan')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Pustakawan/Resources'), for: 'App\\Filament\\Pustakawan\\Resources')
            ->resources([
                BukuResource::class,
                AnggotaResource::class,
                PeminjamanResource::class,
                PengembalianResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pustakawan/Pages'), for: 'App\\Filament\\Pustakawan\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Pustakawan/Widgets'), for: 'App\\Filament\\Pustakawan\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class, 
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
                'role:pustakawan',
            ]);
    }
}
