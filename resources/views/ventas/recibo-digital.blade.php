<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Recibo') }} {{ $venta->folio }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #f2f2f2;
            margin: 0;
            padding: 24px;
            color: #222;
        }
        .recibo {
            max-width: 380px;
            margin: 0 auto;
            background: #fff;
            padding: 24px;
            border: 1px dashed #999;
        }
        .centro { text-align: center; }
        .titulo { font-size: 18px; font-weight: bold; margin: 0 0 4px; }
        .muted { color: #666; font-size: 12px; margin: 2px 0; }
        hr { border: none; border-top: 1px dashed #999; margin: 14px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; border-bottom: 1px solid #999; padding-bottom: 4px; }
        td { padding: 4px 0; vertical-align: top; }
        .num { text-align: right; }
        .fila-total { display: flex; justify-content: space-between; font-size: 14px; margin: 4px 0; }
        .fila-total.grande { font-size: 16px; font-weight: bold; }
        .pie { text-align: center; margin-top: 18px; font-size: 12px; color: #666; }
        @media print {
            body { background: #fff; padding: 0; }
            .recibo { border: none; }
        }
    </style>
</head>
<body>
    <div class="recibo">
        <div class="centro">
            <p class="titulo">{{__("Libreria JILS") }}</p>
            <p class="titulo2">{{ $venta->sucursal->nombre }}</p>
            <p class="muted">{{ __('Recibo digital de venta') }}</p>
            <p class="muted">{{ __('Folio') }}: <strong>{{ $venta->folio }}</strong></p>
            <p class="muted">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
            <p class="muted">{{ __('Atendió') }}: {{ $venta->cajero->name ?? '-' }}</p>
        </div>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>{{ __('Producto') }}</th>
                    <th class="num">{{ __('Cant.') }}</th>
                    <th class="num">{{ __('Importe') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre ?? __('Producto eliminado') }}</td>
                        <td class="num">{{ $detalle->cantidad }}</td>
                        <td class="num">${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <div class="fila-total">
            <span>{{ __('Subtotal') }}</span>
            <span>${{ number_format($venta->subtotal, 2) }}</span>
        </div>
        <div class="fila-total grande">
            <span>{{ __('Total') }}</span>
            <span>${{ number_format($venta->total, 2) }}</span>
        </div>
        <div class="fila-total">
            <span>{{ __('Método de pago') }}</span>
            <span>{{ $venta->metodoPago->nombre ?? '-' }}</span>
        </div>

        @if ($venta->metodoPago && $venta->metodoPago->nombre === 'Efectivo' && ! is_null($venta->monto_recibido))
            <div class="fila-total">
                <span>{{ __('Monto recibido') }}</span>
                <span>${{ number_format($venta->monto_recibido, 2) }}</span>
            </div>
            <div class="fila-total">
                <span>{{ __('Vuelto') }}</span>
                <span>${{ number_format($venta->cambio, 2) }}</span>
            </div>
        @endif

        <div class="pie">
            <p>{{ __('¡Gracias por su compra!') }}</p>
        </div>
    </div>
</body>
</html>
