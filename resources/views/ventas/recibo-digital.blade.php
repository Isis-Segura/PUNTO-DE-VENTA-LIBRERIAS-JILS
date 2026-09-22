<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Recibo') }} {{ $venta->folio }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        @page { margin: 18px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
        }
        .recibo {
            width: 100%;
            border: 1.5px dashed #555;
            padding: 16px 14px;
        }
        .centro { text-align: center; }
        .marca {
            font-size: 17px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .sucursal {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .muted {
            color: #444;
            font-size: 11px;
            margin: 2px 0;
            line-height: 1.3;
        }
        .sep {
            border: none;
            border-top: 1.5px dashed #777;
            margin: 12px 0;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 11.5px;
        }
        table.items th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1.5px solid #333;
            padding: 0 2px 5px 0;
        }
        table.items th.num,
        table.items td.num {
            text-align: right;
        }
        table.items col.c-prod { width: 52%; }
        table.items col.c-cant { width: 16%; }
        table.items col.c-imp  { width: 32%; }
        table.items td {
            padding: 6px 2px 6px 0;
            vertical-align: top;
            border-bottom: 1px dotted #bbb;
            word-wrap: break-word;
        }
        table.items tr:last-child td { border-bottom: none; }
        table.totales {
            width: 100%;
            border-collapse: collapse;
        }
        table.totales td {
            padding: 4px 0;
            font-size: 12px;
        }
        table.totales .label { text-align: left; }
        table.totales .value { text-align: right; }
        table.totales tr.total td {
            font-size: 14px;
            font-weight: bold;
            padding: 7px 0;
            border-top: 1.5px solid #333;
            border-bottom: 1.5px solid #333;
        }
        .pie {
            text-align: center;
            margin-top: 14px;
            font-size: 11px;
            color: #555;
            font-style: italic;
        }
        .pie .brand {
            font-style: normal;
            font-weight: bold;
            display: block;
            margin-top: 3px;
            color: #222;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="recibo">
        <div class="centro">
            <div class="marca">{{ __('Librería JILS') }}</div>
            <div class="sucursal">{{ $venta->sucursal->nombre }}</div>
            <p class="muted">{{ __('Recibo digital de venta') }}</p>
            <p class="muted">{{ __('Folio') }}: <strong>{{ $venta->folio }}</strong></p>
            <p class="muted">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
            <p class="muted">{{ __('Atendió') }}: {{ $venta->cajero->name ?? '-' }}
            </p>
            <p class="muted">{{ __('Caja') }}: {{ $venta->caja->nombre ?? '—' }}</p>
        </div>

        <hr class="sep">

        <table class="items">
            <colgroup>
                <col class="c-prod">
                <col class="c-cant">
                <col class="c-imp">
            </colgroup>
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

        <hr class="sep">

        <table class="totales">
            <tr>
                <td class="label">{{ __('Subtotal') }}</td>
                <td class="value">${{ number_format($venta->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">{{ __('IVA') }} ({{ number_format($venta->tasa_iva ?? 16, 0) }}%)</td>
                <td class="value">${{ number_format($venta->iva ?? 0, 2) }}</td>
            </tr>
            <tr class="total">
                <td class="label">{{ __('Total') }}</td>
                <td class="value">${{ number_format($venta->total, 2) }}</td>
            </tr>
            <tr>
                <td class="label">{{ __('Método de pago') }}</td>
                <td class="value">{{ $venta->metodoPago->nombre ?? '-' }}</td>
            </tr>
            @if ($venta->metodoPago && $venta->metodoPago->nombre === 'Efectivo' && ! is_null($venta->monto_recibido))
                <tr>
                    <td class="label">{{ __('Monto recibido') }}</td>
                    <td class="value">${{ number_format($venta->monto_recibido, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">{{ __('Vuelto') }}</td>
                    <td class="value">${{ number_format($venta->cambio, 2) }}</td>
                </tr>
            @endif
        </table>

        <div class="pie">
            {{ __('¡Gracias por su compra!') }}
            <span class="brand">PDV JILS</span>
        </div>
    </div>
</body>
</html>
