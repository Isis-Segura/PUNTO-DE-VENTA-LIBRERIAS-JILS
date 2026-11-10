@extends('adminlte::page')

@section('title', __('Solicitudes'))

@section('content_header')
    <h1>{{ __('Solicitudes') }}</h1>
@stop

@section('content')
    @once
        @include('partials.app-confirm-modal')
    @endonce

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tipo === 'contrasena' ? 'active' : '' }}"
               href="{{ route('admin.solicitudes.index', ['tipo' => 'contrasena']) }}">
                <i class="fas fa-key mr-1"></i> {{ __('Cambio de contraseña') }}
                @if ($pendientesContrasena > 0)
                    <span class="badge badge-danger ml-1">{{ $pendientesContrasena }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tipo === 'terminos' ? 'active' : '' }}"
               href="{{ route('admin.solicitudes.index', ['tipo' => 'terminos']) }}">
                <i class="fas fa-file-contract mr-1"></i> {{ __('Términos y condiciones') }}
                @if ($pendientesTerminos > 0)
                    <span class="badge badge-danger ml-1">{{ $pendientesTerminos }}</span>
                @endif
            </a>
        </li>
    </ul>

    @if ($tipo === 'terminos')
        @include('admin.solicitudes._terminos')
    @else
        @include('admin.solicitudes._contrasena')
    @endif
@stop
