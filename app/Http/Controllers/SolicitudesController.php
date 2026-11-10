<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Models\TermsRequest;
use Illuminate\Http\Request;

/**
 * Sección "Ver solicitudes": reúne los dos tipos de solicitud
 * (cambio de contraseña y términos y condiciones). Solo Administrador.
 */
class SolicitudesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $tipo = $request->query('tipo') === 'terminos' ? 'terminos' : 'contrasena';

        $pendientesContrasena = PasswordResetRequest::pending()->count();
        $pendientesTerminos = TermsRequest::pending()->count();

        $solicitudes = $tipo === 'terminos'
            ? TermsRequest::with(['user.role', 'attendant'])->latest()->paginate(20)->withQueryString()
            : PasswordResetRequest::with(['user.role', 'attendant'])->latest()->paginate(20)->withQueryString();

        return view('admin.solicitudes.index', compact(
            'tipo', 'solicitudes', 'pendientesContrasena', 'pendientesTerminos'
        ));
    }
}
