@if (Auth::check() && Auth::user()->isAdmin())
@php
    $pendingPass = \App\Models\PasswordResetRequest::pending()->count();
    $pendingTerms = \App\Models\TermsRequest::pending()->count();
    $pending = $pendingPass + $pendingTerms;
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" title="{{ __('Avisos') }}">
        <i class="far fa-bell"></i>
        @if ($pending > 0)
            <span class="badge badge-danger navbar-badge">{{ $pending }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="{{ route('admin.solicitudes.index') }}" class="dropdown-item">
            <i class="fas fa-inbox mr-2"></i> {{ __('Ver solicitudes') }}
            @if ($pending > 0)
                <span class="float-right text-muted text-sm">{{ $pending }}</span>
            @endif
        </a>
    </div>
</li>
@endif
