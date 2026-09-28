<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editPassword()
    {
        $solicitudPendiente = PasswordResetRequest::where('user_id', auth()->id())
            ->pending()
            ->latest()
            ->first();

        return view('profile.password', compact('solicitudPendiente'));
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed'],
        ], [
            'current_password.required' => 'Indica tu contraseña actual.',
            'password.required' => 'Indica la nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.',
            ])->withInput();
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        // Si tenía solicitud pendiente, se marca atendida al cambiarla él mismo
        PasswordResetRequest::where('user_id', $user->id)
            ->pending()
            ->update([
                'status' => 'attended',
                'attended_by' => $user->id,
                'attended_at' => now(),
            ]);

        return redirect()
            ->route('profile.password.edit')
            ->with('success', __('messages.password_updated'));
    }

    /**
     * El usuario no recuerda su contraseña: avisa a los administradores.
     */
    public function requestPasswordHelp(Request $request)
    {
        $user = $request->user();

        $existe = PasswordResetRequest::where('user_id', $user->id)->pending()->exists();

        if ($existe) {
            return back()->with('info', __('messages.password_pending'));
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return back()->with('success', __('messages.password_help_sent'));
    }
}
