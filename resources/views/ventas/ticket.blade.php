@extends('adminlte::page')

@section('title', __('Ticket de venta'))

@section('content_header')
    <h1 class="no-print">{{ __('Ticket de venta') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success no-print">{{ session('success') }}</div>
    @endif

    <div class="ticket-wrap">
        <div class="recibo" id="ticket-recibo">
            <div class="centro">
                <p class="titulo">{{ __('Librería JILS') }}</p>
                <p class="subtitulo">{{ $venta->sucursal->nombre }}</p>
                <p class="muted">{{ __('Recibo de venta') }}</p>
                <p class="muted">{{ __('Folio') }}: <strong>{{ $venta->folio }}</strong></p>
                <p class="muted">{{ $venta->created_at->format('d/m/Y H:i') }}</p>
                <p class="muted">{{ __('Atendió') }}: {{ $venta->cajero->name ?? '-' }}
                </p>
                <p class="muted">{{ __('Caja') }}: {{ $venta->caja->nombre ?? '—' }}</p>
            </div>

            <hr class="dash">

            <table class="ticket-table">
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

            <hr class="dash">

            <div class="fila-total">
                <span>{{ __('Subtotal') }}</span>
                <span>${{ number_format($venta->subtotal, 2) }}</span>
            </div>
            <div class="fila-total">
                <span>{{ __('IVA') }} ({{ number_format($venta->tasa_iva ?? 16, 0) }}%)</span>
                <span>${{ number_format($venta->iva ?? 0, 2) }}</span>
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
                <p class="brand">{{ __('PDV JILS') }}</p>
            </div>
        </div>

        <div class="ticket-actions no-print">
            <button type="button" id="btn-imprimir-ticket" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> {{ __('Imprimir') }}
            </button>
            <button type="button" id="btn-descargar-ticket" class="btn btn-info btn-sm">
                <i class="fas fa-download"></i> {{ __('Descargar imagen') }}
            </button>
            <a href="{{ route('ventas.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nueva venta') }}
            </a>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-sm">
                {{ __('Ver historial') }}
            </a>
        </div>
    </div>
@stop

@section('css')
<style>
    .ticket-wrap {
        max-width: 400px;
        margin: 0 auto 2rem;
    }
    .recibo {
        font-family: 'Courier New', Courier, monospace;
        background: #fff;
        color: #222;
        padding: 28px 24px;
        border: 1px dashed #999;
        border-radius: 4px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }
    .recibo .centro { text-align: center; }
    .recibo .titulo {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 4px;
    }
    .recibo .subtitulo {
        font-size: 1rem;
        margin: 0 0 8px;
        font-weight: 600;
    }
    .recibo .muted {
        color: #666;
        font-size: 0.8rem;
        margin: 2px 0;
    }
    .recibo hr.dash {
        border: none;
        border-top: 1px dashed #999;
        margin: 14px 0;
    }
    .ticket-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .ticket-table th {
        text-align: left;
        border-bottom: 1px solid #999;
        padding-bottom: 4px;
        font-weight: 700;
    }
    .ticket-table td { padding: 5px 0; vertical-align: top; }
    .ticket-table .num,
    .ticket-table th.num { text-align: right; }
    .fila-total {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin: 4px 0;
    }
    .fila-total.grande {
        font-size: 1.05rem;
        font-weight: 700;
        margin-top: 6px;
    }
    .pie {
        text-align: center;
        margin-top: 18px;
        font-size: 0.8rem;
        color: #666;
    }
    .pie .brand {
        margin: 4px 0 0;
        font-weight: 700;
        color: #222;
        font-size: 0.75rem;
    }
    .ticket-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-top: 1.25rem;
    }

    @media print {
        @page { margin: 12mm; }
        html, body { background: #fff !important; }
        body * { visibility: hidden; }
        .ticket-wrap, .ticket-wrap * { visibility: visible; }
        .ticket-wrap {
            position: absolute;
            left: 0;
            top: 0;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
        }
        .recibo {
            width: 100% !important;
            max-width: 100% !important;
            box-shadow: none !important;
            border: 1.5px dashed #555 !important;
            padding: 28px 32px !important;
            font-size: 16px !important;
        }
        .recibo .titulo { font-size: 1.6rem !important; }
        .recibo .subtitulo { font-size: 1.25rem !important; }
        .recibo .muted { font-size: 1rem !important; }
        .ticket-table { font-size: 1.05rem !important; }
        .ticket-table th, .ticket-table td { padding: 8px 4px !important; }
        .fila-total { font-size: 1.1rem !important; }
        .fila-total.grande { font-size: 1.35rem !important; }
        .pie { font-size: 1rem !important; margin-top: 24px !important; }
        .pie .brand { font-size: 0.95rem !important; }
        .recibo hr.dash { margin: 18px 0 !important; }
        .no-print,
        .main-sidebar,
        .main-header,
        .content-header,
        .main-footer {
            display: none !important;
            visibility: hidden !important;
        }
    }
</style>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function () {
    var folio = @json($venta->folio);
    var recibo = document.getElementById('ticket-recibo');
    var btnImg = document.getElementById('btn-descargar-ticket');
    var btnPrint = document.getElementById('btn-imprimir-ticket');

    function capturar() {
        if (!window.html2canvas || !recibo) {
            alert('No se pudo generar la imagen. Recarga la página e intenta de nuevo.');
            return Promise.reject();
        }
        return html2canvas(recibo, {
            backgroundColor: '#ffffff',
            scale: 2,
            useCORS: true,
            logging: false
        });
    }

    if (btnImg) {
        btnImg.addEventListener('click', function () {
            var original = btnImg.innerHTML;
            btnImg.disabled = true;
            btnImg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando…';

            capturar().then(function (canvas) {
                var link = document.createElement('a');
                link.download = 'recibo-' + folio + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }).catch(function () {
                // ignore
            }).finally(function () {
                btnImg.disabled = false;
                btnImg.innerHTML = original;
            });
        });
    }

    if (btnPrint) {
        btnPrint.addEventListener('click', function () {
            window.print();
        });
    }
})();
</script>
@stop
