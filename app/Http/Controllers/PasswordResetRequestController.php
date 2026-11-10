<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;

class PasswordResetRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        return redirect()->route('admin.solicitudes.index', ['tipo' => 'contrasena']);
    }

    public function attend(PasswordResetRequest $passwordResetRequest)
    {
        if ($passwordResetRequest->status !== 'pending') {
            return back()->with('info', __('messages.request_attended'));
        }

        $passwordResetRequest->update([
            'status' => 'attended',
            'attended_by' => auth()->id(),
            'attended_at' => now(),
        ]);

        return back()->with(
            'success',
            'Solicitud marcada como atendida. Ahora puedes editar al usuario y asignarle una nueva contraseña desde Usuarios.'
        );
    }

    public function destroy(PasswordResetRequest $passwordResetRequest)
    {
        $passwordResetRequest->delete();

        return back()->with('success', __('messages.request_deleted'));
    }
}
