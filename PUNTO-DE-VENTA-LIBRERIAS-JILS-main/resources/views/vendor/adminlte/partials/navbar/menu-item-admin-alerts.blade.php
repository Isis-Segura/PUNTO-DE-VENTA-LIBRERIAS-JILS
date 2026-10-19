@if (Auth::check() && Auth::user()->isAdmin())
@php
    $pending = \App\Models\PasswordResetRequest::pending()->count();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" title="{{ __('Avisos') }}">
        <i class="far fa-bell"></i>
        @if ($pending > 0)
            <span class="badge badge-danger navbar-badge">{{ $pending }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">
            @if ($pending > 0)
                {{ $pending }} {{ __('solicitud(es) de contraseña') }}
            @else
                {{ __('Sin avisos pendientes') }}
            @endif
        </span>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.password-requests.index') }}" class="dropdown-item">
            <i class="fas fa-key mr-2"></i> {{ __('Ver solicitudes') }}
            @if ($pending > 0)
                <span class="float-right text-muted text-sm">{{ $pending }}</span>
            @endif
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.password-requests.index') }}" class="dropdown-item dropdown-footer">
            {{ __('Abrir panel de solicitudes') }}
        </a>
    </div>
</li>
@endif
