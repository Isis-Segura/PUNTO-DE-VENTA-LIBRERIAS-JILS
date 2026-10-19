<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Admin → /admin ; Gerente y Cajero → /home
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            return '/admin';
        }

        return '/home';
    }

    /**
     * Solo permite login si la cuenta está activa.
     */
    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), 'password') + ['activo' => true];
    }

    /**
     * Mensaje claro si la cuenta está desactivada;
     * mensaje genérico si el email/contraseña fallan.
     *
     * IMPORTANTE: no usar parent::sendFailedLoginResponse() —
     * el método vive en el trait AuthenticatesUsers, no en Controller.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where($this->username(), $request->input($this->username()))->first();

        if (
            $user
            && ! $user->activo
            && Hash::check($request->input('password'), $user->password)
        ) {
            throw ValidationException::withMessages([
                $this->username() => ['Tu cuenta está desactivada. Contacta al Administrador General.'],
            ]);
        }

        // Misma respuesta que el trait original (contraseña o email incorrectos)
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
