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
        $solicitudes = PasswordResetRequest::with(['user.role', 'attendant'])
            ->latest()
            ->paginate(20);

        return view('admin.password_requests.index', compact('solicitudes'));
    }

    public function attend(PasswordResetRequest $passwordResetRequest)
    {
        if ($passwordResetRequest->status !== 'pending') {
            return back()->with('info', 'Esta solicitud ya fue atendida.');
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

        return back()->with('success', 'Solicitud eliminada correctamente.');
    }
}
