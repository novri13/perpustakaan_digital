<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Middleware\RoleMiddleware;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use App\Responses\AnggotaLoginResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan alias middleware 'role'
        app('router')->aliasMiddleware('role', RoleMiddleware::class);
    }
}
