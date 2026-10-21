<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // AdminLTE usa Bootstrap: la paginación debe verse con estilos Bootstrap,
        // no Tailwind (si no, la parte de abajo de tablas se ve rota).
        Paginator::useBootstrap();

        // Forzar HTTPS en producción para que el navegador cargue los estilos y scripts sin bloqueo de contenido mixto
        if (config('app.env') === 'production' || str_starts_with(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Gates del menú lateral (config/adminlte.php, clave 'can')
        Gate::define('es-admin', fn ($user) => $user->isAdmin());
        Gate::define('es-admin-o-gerente', fn ($user) => $user->isAdmin() || $user->isGerente());
        Gate::define('puede-vender', fn ($user) => $user->isAdmin() || $user->isGerente() || $user->isCajero());
    }
}
