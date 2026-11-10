<?php

namespace App\Http\Controllers;

use App\Mail\TerminosCondicionesMail;
use App\Models\TermsRequest;
use Illuminate\Support\Facades\Mail;

/**
 * Solicitudes de usuarios para volver a ver los términos y condiciones.
 * Solo el Administrador. Aprobar = reenviar el correo con un enlace nuevo (un solo uso).
 */
class TermsRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function approve(TermsRequest $termsRequest)
    {
        if (! $termsRequest->isPending()) {
            return back()->with('info', __('messages.request_attended'));
        }

        $usuario = $termsRequest->user;

        if (! $usuario) {
            $termsRequest->delete();

            return back()->with('info', __('messages.request_deleted'));
        }

        try {
            Mail::to($usuario->email)->send(
                new TerminosCondicionesMail($usuario, $usuario->urlAceptarTerminos())
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', __('messages.terms_resend_failed'));
        }

        $termsRequest->update([
            'status' => 'attended',
            'attended_by' => auth()->id(),
            'attended_at' => now(),
        ]);

        return back()->with('success', __('messages.terms_resent', ['email' => $usuario->email]));
    }

    public function destroy(TermsRequest $termsRequest)
    {
        $termsRequest->delete();

        return back()->with('success', __('messages.request_deleted'));
    }
}
