@extends('adminlte::page')

@section('title', __('Usuarios'))

@section('content_header')
    <h1>{{ __('Usuarios') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Listado de usuarios') }}</span>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nuevo usuario') }}
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Correo') }}</th>
                        <th>{{ __('Rol') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->role->nombre ?? __('Sin rol') }}</td>
                            <td>
                                @if ($usuario->activo)
                                    <span class="badge badge-success">{{ __('Activo') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactivo') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('¿Eliminar este usuario?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">{{ __('No hay usuarios registrados.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $usuarios->links() }}
        </div>
    </div>
@stop
