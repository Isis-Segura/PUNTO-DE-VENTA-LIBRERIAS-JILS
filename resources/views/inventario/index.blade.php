@extends('adminlte::page')

@section('title', __('Inventario'))

@section('content_header')
    <h1>{{ __('Inventario') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" class="form-inline">
                @if ($sucursales->count() > 1)
                    <label class="mr-2 mb-0">{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control form-control-sm mr-3" onchange="this.form.submit()">
                        <option value="">{{ __('Todas') }}</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ request('sucursal_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="bajo_stock" name="bajo_stock" value="1"
                           {{ request('bajo_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="custom-control-label" for="bajo_stock">{{ __('Solo mostrar bajo inventario') }}</label>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Producto') }}</th>
                        <th>{{ __('Sucursal') }}</th>
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
                            <td>{{ $inv->producto->sucursal->nombre ?? '-' }}</td>
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
                            <td colspan="7" class="text-center py-3">{{ __('No hay productos en inventario.') }}</td>
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
