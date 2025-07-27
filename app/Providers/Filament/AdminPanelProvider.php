<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\Dashboard;
use App\Filament\Admin\Resources\BukuResource;
use App\Filament\Admin\Resources\AnggotaResource;
use App\Filament\Admin\Resources\JurusanResource;
use App\Filament\Admin\Resources\KategoriResource;
use App\Filament\Admin\Resources\PenerbitResource;
use App\Filament\Admin\Resources\RakResource;
use App\Filament\Admin\Resources\PeminjamanResource;
use App\Filament\Admin\Resources\PengembalianResource;
use App\Filament\Admin\Pages\LaporanPeminjaman;
use App\Filament\Admin\Pages\LaporanPengembalian;
use App\Filament\Admin\Resources\DendaResource;
use App\Filament\Admin\Resources\TransaksiDendaResource;
use App\Filament\Resources\BookingResource;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin') // semua login lewat /admin/login
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                BukuResource::class,
                AnggotaResource::class,
                JurusanResource::class,
                KategoriResource::class,
                PenerbitResource::class,
                RakResource::class,
                PeminjamanResource::class,
                PengembalianResource::class,
                DendaResource::class,
                TransaksiDendaResource::class,
                BookingResource::class,
            ])
            ->pages([
                Dashboard::class,
                LaporanPeminjaman::class,
                LaporanPengembalian::class,
            ])
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
            ]);
    }
}
