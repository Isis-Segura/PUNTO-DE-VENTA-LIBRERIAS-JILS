<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Página pública (sin login) a la que lleva el enlace del correo de bienvenida.
 *
 *  - Si el usuario todavía no acepta: muestra los términos con "Aceptar" y
 *    "Rechazar". Rechazar no llega al servidor (no se guarda nada) y al volver a
 *    entrar se le pregunta de nuevo.
 *  - Si ya aceptó: la aceptación queda guardada y no se puede deshacer. La página
 *    muestra sus datos de acceso (correo, rol y contraseña) y deja releer los términos.
 *
 * Ambas rutas usan el middleware "signed": solo funcionan con el enlace firmado y
 * vigente de ese usuario. Aceptar es un POST (no un GET) para que los antivirus o
 * previsualizadores de enlaces de los correos no "acepten" solos.
 */
class TerminosController extends Controller
{
    public function show(Request $request, User $usuario)
    {
        $aceptado = $usuario->haAceptadoTerminos();

        return view('terminos.aceptar', [
            'usuario' => $usuario,
            'aceptado' => $aceptado,
            'recienAceptado' => (bool) session('terminos_recien_aceptados'),
            // Los datos de acceso SOLO se calculan/envían a la vista si ya aceptó.
            'rolNombre' => $aceptado ? $usuario->nombreDeRol() : null,
            'password' => $aceptado ? $usuario->credencialesIniciales() : null,
            // El formulario envía el POST a esta misma URL firmada.
            'accionUrl' => $request->fullUrl(),
        ]);
    }

    public function accept(Request $request, User $usuario)
    {
        // Si ya había aceptado no se vuelve a escribir (conserva la fecha original).
        if (! $usuario->haAceptadoTerminos()) {
            $usuario->forceFill(['terminos_aceptados_at' => now()])->save();
            $request->session()->flash('terminos_recien_aceptados', true);
        }

        // Post/Redirect/Get: al recargar no se reenvía el formulario.
        return redirect()->to($request->fullUrl());
    }
}
