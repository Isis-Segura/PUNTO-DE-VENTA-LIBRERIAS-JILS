<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * A dónde mandar al usuario según su rol una vez que inicia sesión.
     * El Administrador General va a su panel; Gerentes y Cajeros van a /home
     * (sus paneles propios se agregarán cuando se desarrollen esos módulos).
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            return '/admin';
        }

        return '/home';
    }
}
