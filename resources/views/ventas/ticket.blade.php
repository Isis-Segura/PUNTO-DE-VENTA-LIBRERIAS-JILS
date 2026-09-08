@extends('adminlte::page')

@section('title', __('Ticket de venta'))

@section('content_header')
    <h1>{{ __('Ticket de venta') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <div class="card-body">
            <div class="text-center mb-3">
                <h4>{{ $venta->sucursal->nombre }}</h4>
                <p class="mb-0">{{ __('Folio') }}: <strong>{{ $venta->folio }}</strong></p>
                <p class="mb-0">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-0">{{ __('Atendió') }}: {{ $venta->cajero->name ?? '-' }}</p>
            </div>

            <hr>

            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('Producto') }}</th>
                        <th class="text-center">{{ __('Cant.') }}</th>
                        <th class="text-right">{{ __('Precio') }}</th>
                        <th class="text-right">{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre ?? __('Producto eliminado') }}</td>
                            <td class="text-center">{{ $detalle->cantidad }}</td>
                            <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td class="text-right">${{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <div class="d-flex justify-content-between">
                <strong>{{ __('Subtotal') }}:</strong>
                <span>${{ number_format($venta->subtotal, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>{{ __('Total') }}:</strong>
                <strong>${{ number_format($venta->total, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <strong>{{ __('Método de pago') }}:</strong>
                <span>{{ $venta->metodoPago->nombre ?? '-' }}</span>
            </div>

            @if ($venta->metodoPago && $venta->metodoPago->nombre === 'Efectivo' && ! is_null($venta->monto_recibido))
                <div class="d-flex justify-content-between">
                    <strong>{{ __('Monto recibido') }}:</strong>
                    <span>${{ number_format($venta->monto_recibido, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <strong>{{ __('Vuelto') }}:</strong>
                    <span>${{ number_format($venta->cambio, 2) }}</span>
                </div>
            @endif

            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i> {{ __('Imprimir') }}
                </button>
                <a href="{{ route('ventas.recibo-digital', $venta) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-file-download"></i> {{ __('Descargar recibo digital') }}
                </a>
                <a href="{{ route('ventas.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> {{ __('Nueva venta') }}
                </a>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary btn-sm">
                    {{ __('Ver historial') }}
                </a>
            </div>
        </div>
    </div>
@stop
