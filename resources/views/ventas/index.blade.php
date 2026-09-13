@extends('adminlte::page')

@section('title', __('Ventas'))

@section('content_header')
    <h1>{{ __('Historial de ventas') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('ventas.create') }}" class="btn btn-success">
            <i class="fas fa-cash-register"></i> {{ __('Nueva venta') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <form method="GET" class="form-inline">
                @if ($sucursales->count() > 1)
                    <label class="mr-2 mb-0">{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control form-control-sm mr-3">
                        <option value="">{{ __('Todas') }}</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ request('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                @endif

                <label class="mr-2 mb-0">{{ __('Desde') }}</label>
                <input type="date" name="desde" class="form-control form-control-sm mr-3" value="{{ request('desde') }}">

                <label class="mr-2 mb-0">{{ __('Hasta') }}</label>
                <input type="date" name="hasta" class="form-control form-control-sm mr-3" value="{{ request('hasta') }}">

                <button type="submit" class="btn btn-sm btn-primary">{{ __('Filtrar') }}</button>
            </form>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Folio') }}</th>
                        <th>{{ __('Fecha') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Cajero') }}</th>
                        <th>{{ __('Método de pago') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->folio }}</td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $venta->sucursal->nombre ?? '-' }}</td>
                            <td>{{ $venta->cajero->name ?? '-' }}</td>
                            <td>{{ $venta->metodoPago->nombre ?? '-' }}</td>
                            <td>${{ number_format($venta->total, 2) }}</td>
                            <td class="text-right">
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-receipt"></i> {{ __('Ver ticket') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">{{ __('No hay ventas registradas.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $ventas->links() }}
        </div>
    </div>
@stop
