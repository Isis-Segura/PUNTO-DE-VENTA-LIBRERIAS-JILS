<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Gates usados por el menú lateral (config/adminlte.php, clave 'can')
        // para mostrar u ocultar cada opción según el rol del usuario.
        Gate::define('es-admin', fn ($user) => $user->isAdmin());
        Gate::define('es-admin-o-gerente', fn ($user) => $user->isAdmin() || $user->isGerente());
        Gate::define('puede-vender', fn ($user) => $user->isAdmin() || $user->isGerente() || $user->isCajero());
    }
}
