@extends('adminlte::page')

@section('title', __('Productos'))

@section('content_header')
    <h1>{{ __('Productos') }}</h1>
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
            <span>{{ __('Listado de productos') }}</span>
            <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nuevo producto') }}
            </a>
        </div>

        @if ($sucursales->count() > 1)
            <div class="card-body border-bottom py-2">
                <form method="GET" class="form-inline">
                    <label class="mr-2 mb-0">{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">{{ __('Todas') }}</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ request('sucursal_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Categoría') }}</th>
                        <th>{{ __('Precio') }}</th>
                        <th>{{ __('Existencia') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->sucursal->nombre ?? '-' }}</td>
                            <td>{{ $producto->categoria->nombre ?? '-' }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>
                                {{ $producto->inventario?->cantidad ?? 0 }}
                                @if ($producto->inventario && $producto->inventario->bajo_inventario)
                                    <span class="badge badge-danger">{{ __('Bajo stock') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($producto->activo)
                                    <span class="badge badge-success">{{ __('Activo') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactivo') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('¿Eliminar este producto?') }}');">
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
                            <td colspan="7" class="text-center py-3">{{ __('No hay productos registrados.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $productos->links() }}
        </div>
    </div>
@stop
