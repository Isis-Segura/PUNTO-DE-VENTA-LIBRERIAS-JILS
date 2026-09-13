@extends('adminlte::page')

@section('title', __('Sucursal').' - '.$sucursal->nombre)

@section('content_header')
    <h1>{{ $sucursal->nombre }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('sucursales.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('Volver a sucursales') }}
        </a>
    </div>

    {{-- Datos de la sucursal --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Datos de la sucursal') }}</span>
            @can('es-admin')
                <a href="{{ route('sucursales.edit', $sucursal) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> {{ __('Editar') }}
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>{{ __('Dirección') }}:</strong><br>
                    {{ $sucursal->direccion ?: '-' }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('Teléfono') }}:</strong><br>
                    {{ $sucursal->telefono ?: '-' }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('Gerente') }}:</strong><br>
                    {{ $sucursal->gerente->name ?? __('Sin asignar') }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('Estado') }}:</strong><br>
                    @if ($sucursal->activa)
                        <span class="badge badge-success">{{ __('Activa') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('Inactiva') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Inventario de esta sucursal --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>{{ __('Inventario') }}</span>
                <form method="GET" class="form-inline mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="bajo_stock" name="bajo_stock" value="1"
                               {{ request('bajo_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="custom-control-label" for="bajo_stock">{{ __('Solo mostrar bajo inventario') }}</label>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Producto') }}</th>
                        <th>{{ __('Categoría') }}</th>
                        <th>{{ __('Existencia') }}</th>
                        <th>{{ __('Stock mínimo') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventarios as $inv)
                        <tr class="{{ $inv->bajo_inventario ? 'table-danger' : '' }}">
                            <td>{{ $inv->producto->nombre ?? '-' }}</td>
                            <td>{{ $inv->producto->categoria->nombre ?? '-' }}</td>
                            <td>{{ $inv->cantidad }}</td>
                            <td>{{ $inv->stock_minimo }}</td>
                            <td>
                                @if ($inv->bajo_inventario)
                                    <span class="badge badge-danger">{{ __('Bajo stock') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('OK') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-sm btn-warning"
                                        data-toggle="modal" data-target="#ajustar-{{ $inv->id }}">
                                    <i class="fas fa-edit"></i> {{ __('Ajustar') }}
                                </button>
                            </td>
                        </tr>

                        {{-- Modal para ajustar cantidad --}}
                        <div class="modal fade" id="ajustar-{{ $inv->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('inventario.update', $inv) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('Ajustar inventario') }}: {{ $inv->producto->nombre ?? '' }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>{{ __('Cantidad disponible') }}</label>
                                                <input type="number" min="0" name="cantidad" class="form-control" value="{{ $inv->cantidad }}">
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Stock mínimo (alerta)') }}</label>
                                                <input type="number" min="0" name="stock_minimo" class="form-control" value="{{ $inv->stock_minimo }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancelar') }}</button>
                                            <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">{{ __('Esta sucursal no tiene productos en inventario.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $inventarios->links() }}
        </div>
    </div>
@stop
