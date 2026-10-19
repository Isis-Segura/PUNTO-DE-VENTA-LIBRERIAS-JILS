@extends('adminlte::page')

@section('title', __('Cambiar contraseña'))

@section('content_header')
    <h1>{{ __('Cambiar contraseña') }}</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>{{ __('Contraseña actual') }}</label>
                    <input type="password" name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Nueva contraseña') }}</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Confirmar nueva contraseña') }}</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Guardar cambios') }}</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('¿Olvidaste tu contraseña?') }}</h3>
        </div>
        <div class="card-body">
            @if ($solicitudPendiente)
                <p class="text-muted mb-0">
                    {{ __('Ya avisaste a los administradores el') }}
                    {{ $solicitudPendiente->created_at->format('d/m/Y H:i') }}.
                    {{ __('Un administrador te ayudará pronto.') }}
                </p>
            @else
                <p>{{ __('Si no recuerdas tu contraseña actual, avisa a un administrador para que te ayude a restablecerla.') }}</p>
                <form action="{{ route('profile.password.help') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        {{ __('Avisar a un administrador') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@stop
