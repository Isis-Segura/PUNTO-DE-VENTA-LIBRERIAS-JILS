@extends('adminlte::page')

@section('title', __('Sucursales'))

@section('content_header')
    <h1>{{ __('Sucursales') }}</h1>
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
            <span>{{ __('Listado de sucursales') }}</span>
            <a href="{{ route('sucursales.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nueva sucursal') }}
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Dirección') }}</th>
                        <th>{{ __('Teléfono') }}</th>
                        <th>{{ __('Gerente') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sucursales as $sucursal)
                        <tr>
                            <td>{{ $sucursal->nombre }}</td>
                            <td>{{ $sucursal->direccion }}</td>
                            <td>{{ $sucursal->telefono }}</td>
                            <td>{{ $sucursal->gerente->name ?? __('Sin asignar') }}</td>
                            <td>
                                @if ($sucursal->activa)
                                    <span class="badge badge-success">{{ __('Activa') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactiva') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('sucursales.edit', $sucursal) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('sucursales.destroy', $sucursal) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('¿Eliminar esta sucursal?') }}');">
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
                            <td colspan="6" class="text-center py-3">{{ __('No hay sucursales registradas.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $sucursales->links() }}
        </div>
    </div>
@stop
