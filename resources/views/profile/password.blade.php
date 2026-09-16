@extends('adminlte::page')

@section('title', __('Cambiar contraseña'))

@section('content_header')
    <h1>{{ __('Cambiar contraseña') }}</h1>
@stop

@section('css')
<style>
    .pwd-wrap { max-width: 520px; }
    .pwd-card {
        border: none; border-radius: 16px; overflow: hidden;
        box-shadow: 0 8px 30px rgba(15,23,42,.08);
        background: #fff;
    }
    .pwd-hero {
        background: linear-gradient(135deg, #4f46e5, #7c3aed 55%, #a855f7);
        color: #fff; padding: 1.4rem 1.5rem;
    }
    .pwd-hero h2 { margin: 0; font-size: 1.25rem; font-weight: 800; }
    .pwd-hero p { margin: 0.35rem 0 0; opacity: .9; font-size: .9rem; }
    .pwd-body { padding: 1.5rem 1.5rem 1.25rem; }
    .pwd-forgot {
        margin-top: 1.25rem; padding-top: 1.15rem;
        border-top: 1px dashed #e2e8f0;
    }
</style>
@stop

@section('content')
<div class="pwd-wrap">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="pwd-card">
        <div class="pwd-hero">
            <h2><i class="fas fa-key mr-1"></i> {{ __('Seguridad de la cuenta') }}</h2>
            <p>{{ auth()->user()->name }} · {{ auth()->user()->email }}</p>
        </div>
        <div class="pwd-body">
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>{{ __('Contraseña actual') }}</label>
                    <input type="password" name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           required autocomplete="current-password">
                    @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Nueva contraseña') }}</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">{{ __('Mínimo 8 caracteres.') }}</small>
                </div>

                <div class="form-group">
                    <label>{{ __('Confirmar nueva contraseña') }}</label>
                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-save"></i> {{ __('Guardar contraseña') }}
                </button>
            </form>

            <div class="pwd-forgot">
                <p class="text-muted mb-2" style="font-size:.9rem;">
                    <i class="fas fa-question-circle"></i>
                    {{ __('¿Olvidaste tu contraseña actual y no puedes cambiarla?') }}
                </p>
                @if ($solicitudPendiente ?? null)
                    <div class="alert alert-info mb-0 py-2">
                        {{ __('Ya avisaste a los administradores. Estado: pendiente de atención.') }}
                    </div>
                @else
                    <form method="POST" action="{{ route('profile.password.help') }}"
                          onsubmit="return confirm('¿Enviar aviso a los administradores?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-bell"></i> {{ __('Olvidé mi contraseña — avisar a un admin') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
