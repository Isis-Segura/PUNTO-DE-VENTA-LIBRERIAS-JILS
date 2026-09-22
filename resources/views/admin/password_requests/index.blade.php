@extends('adminlte::page')

@section('title', __('Solicitudes de contraseña'))

@section('content_header')
    <h1>{{ __('Solicitudes de contraseña') }}</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Usuario') }}</th>
                        <th>{{ __('Rol') }}</th>
                        <th>{{ __('Fecha') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th>{{ __('Atendido por') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitudes as $s)
                        <tr>
                            <td>
                                <strong>{{ $s->user->name ?? '—' }}</strong><br>
                                <small class="text-muted">{{ $s->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $s->user->role->nombre ?? ($s->user->role->name ?? '—') }}</td>
                            <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($s->status === 'pending')
                                    <span class="badge badge-warning">{{ __('Pendiente') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('Atendida') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($s->attendant)
                                    {{ $s->attendant->name }}
                                    <br><small class="text-muted">{{ optional($s->attended_at)->format('d/m/Y H:i') }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-right text-nowrap">
                                @if ($s->status === 'pending')
                                    <form action="{{ route('admin.password-requests.attend', $s) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">{{ __('Marcar atendida') }}</button>
                                    </form>
                                    @if ($s->user)
                                        <a href="{{ route('admin.usuarios.edit', $s->user) }}" class="btn btn-sm btn-warning">
                                            {{ __('Editar usuario') }}
                                        </a>
                                    @endif
                                @endif
                                <form action="{{ route('admin.password-requests.destroy', $s) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm(@json(__('¿Eliminar esta solicitud de contraseña?')));">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Eliminar') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">{{ __('No hay solicitudes.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($solicitudes->hasPages())
            <div class="card-footer">{{ $solicitudes->links() }}</div>
        @endif
    </div>
@stop
