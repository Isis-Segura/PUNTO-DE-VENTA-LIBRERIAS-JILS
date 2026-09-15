<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Corrige el bug de seguridad donde, después de cerrar sesión, presionar el
 * botón "Atrás" del navegador seguía mostrando una vista de la interfaz ya
 * autenticada (el navegador la servía desde su caché / back-forward cache
 * en lugar de pedirla de nuevo al servidor).
 *
 * Con estos encabezados le decimos al navegador que NUNCA guarde en caché
 * las páginas que pasan por aquí, así que al dar "Atrás" se ve forzado a
 * volver a pedir la página al servidor, el cual redirige a /login si ya
 * no hay sesión activa.
 */
class PreventBackHistoryCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
