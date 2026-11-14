<?php

namespace App\Http\Controllers;

use App\Models\TermsRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Página pública (sin login) a la que lleva el enlace del correo de bienvenida.
 *
 * El enlace es de UN SOLO USO:
 *  - Se gasta cuando el usuario decide (Aceptar o Rechazar). Aceptar queda guardado
 *    en "terminos_aceptados_at" y no se puede deshacer; Rechazar no guarda la
 *    aceptación, pero también gasta el enlace.
 *  - Si el usuario ya había aceptado y recibe un enlace nuevo (porque un administrador
 *    aprobó su solicitud), el enlace se gasta al abrirlo.
 *  - Con un enlace gastado (o reemplazado por otro más nuevo) se muestra la página
 *    "no disponible", desde donde el usuario puede solicitar a los administradores
 *    verlo otra vez. Al aprobarlo, el administrador reenvía el correo con un enlace nuevo.
 *
 * Todas las rutas usan el middleware "signed". Aceptar/Rechazar es un POST (no un GET)
 * para que los antivirus o previsualizadores de enlaces no decidan por el usuario.
 */
class TerminosController extends Controller
{
    public function show(Request $request, User $usuario)
    {
        $aceptado = $usuario->haAceptadoTerminos();

        // Justo después de aceptar se muestra (una vez) la página con sus datos de acceso.
        $recienAceptado = (bool) session('terminos_recien_aceptados');

        if (! $recienAceptado) {
            if (! $usuario->enlaceTerminosDisponible($request->query('t'))) {
                return $this->noDisponible($usuario);
            }

            // Quien ya aceptó antes no tiene botones: ver la página basta para gastar el enlace.
            if ($aceptado) {
                $usuario->gastarEnlaceTerminos();
            }
        }

        return view('terminos.aceptar', [
            'usuario' => $usuario,
            'aceptado' => $aceptado,
            'recienAceptado' => $recienAceptado,
            // Los datos de acceso SOLO se calculan/envían a la vista si ya aceptó.
            'rolNombre' => $aceptado ? $usuario->nombreDeRol() : null,
            'password' => $aceptado ? $usuario->credencialesIniciales() : null,
            // El formulario envía el POST a esta misma URL firmada.
            'accionUrl' => $request->fullUrl(),
        ]);
    }

    public function accept(Request $request, User $usuario)
    {
        $decision = $request->input('decision', 'accept');

        // Enlace ya gastado o reemplazado: no se puede decidir de nuevo.
        if (! $usuario->enlaceTerminosDisponible($request->query('t'))) {
            return $request->expectsJson()
                ? response()->json(['disponible' => false], 410)
                : redirect()->to($request->fullUrl());
        }

        if ($decision === 'reject') {
            // Rechazar: no se guarda la aceptación, pero el enlace se gasta.
            $usuario->gastarEnlaceTerminos();

            return response()->noContent();
        }

        // Si ya había aceptado no se vuelve a escribir (conserva la fecha original).
        if (! $usuario->haAceptadoTerminos()) {
            $usuario->forceFill(['terminos_aceptados_at' => now()])->save();
        }

        $usuario->gastarEnlaceTerminos();
        $request->session()->flash('terminos_recien_aceptados', true);

        // Post/Redirect/Get: al recargar no se reenvía el formulario.
        return redirect()->to($request->fullUrl());
    }

    /**
     * El usuario pide a los administradores que le vuelvan a enviar los términos.
     */
    public function solicitar(Request $request, User $usuario)
    {
        $yaPendiente = TermsRequest::where('user_id', $usuario->id)->pending()->exists();

        if (! $yaPendiente) {
            TermsRequest::create(['user_id' => $usuario->id, 'status' => 'pending']);
        }

        return $this->noDisponible($usuario, enviada: true, yaPendiente: $yaPendiente);
    }

    private function noDisponible(User $usuario, bool $enviada = false, bool $yaPendiente = false)
    {
        $pendiente = $enviada
            ? true
            : TermsRequest::where('user_id', $usuario->id)->pending()->exists();

        return response()->view('terminos.no_disponible', [
            'usuario' => $usuario,
            'pendiente' => $pendiente,
            'enviada' => $enviada && ! $yaPendiente,
            'solicitarUrl' => URL::temporarySignedRoute(
                'terminos.solicitar',
                now()->addDay(),
                ['usuario' => $usuario->getKey()]
            ),
        ], 410);
    }
}
