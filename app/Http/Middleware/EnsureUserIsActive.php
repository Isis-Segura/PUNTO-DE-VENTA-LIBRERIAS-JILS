<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Corrige el bug donde el campo "Inactivo" de un usuario no bloqueaba nada:
 * un empleado despedido/desactivado podía seguir usando el sistema con la
 * sesión que ya tenía abierta. Esta clase revisa, en cada petición
 * autenticada, si el usuario sigue activo; si no, lo desloguea de inmediato.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->activo) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'Tu cuenta ha sido desactivada. Contacta al Administrador General.');
        }

        return $next($request);
    }
}
