@extends('adminlte::page')

@section('title', __('Productos'))

@section('content_header')
    <h1>{{ __('Productos') }}</h1>
@stop

@section('css')
<style>
    .libro-grid { margin-left: -8px; margin-right: -8px; }
    .libro-grid > [class*="col-"] { padding-left: 8px; padding-right: 8px; }

    .libro-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.08);
        transition: transform .15s ease, box-shadow .15s ease;
        height: 100%;
        background: #fff;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }
    .libro-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
    }
    .libro-portada {
        position: relative;
        width: 100%;
        aspect-ratio: 2 / 3;
        background: linear-gradient(145deg, #1e293b, #475569);
        overflow: hidden;
    }
    .libro-portada img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
    }
    .libro-portada .placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.45); font-size: 2.8rem;
    }
    .libro-body {
        padding: 0.85rem 0.95rem 1rem;
        flex: 1; display: flex; flex-direction: column;
    }
    .libro-titulo {
        font-weight: 700; font-size: 0.92rem; line-height: 1.3;
        margin: 0 0 0.35rem; color: #0f172a;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; min-height: 2.4em;
    }
    .libro-meta { font-size: 0.75rem; color: #64748b; margin-bottom: 0.45rem; }
    .libro-precio { font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-top: auto; }
    .libro-badges {
        position: absolute; top: 8px; left: 8px; right: 8px;
        display: flex; justify-content: space-between; gap: 4px; z-index: 1;
    }
    .libro-actions { display: flex; gap: 0.4rem; margin-top: 0.6rem; }
    .libro-actions form { flex: 1; margin: 0; }
    .libro-actions .btn { width: 100%; }

    /* Modal detalle */
    .prod-detail-modal .modal-dialog { max-width: 520px; }
    .prod-detail-hero {
        position: relative;
        height: 220px;
        background: #1e293b;
        overflow: hidden;
        border-radius: 0.3rem 0.3rem 0 0;
    }
    .prod-detail-hero img {
        width: 100%; height: 100%;
        object-fit: cover; object-position: center top;
        filter: brightness(0.55) saturate(0.7);
        transform: scale(1.05);
    }
    .prod-detail-hero .hero-ph {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.35); font-size: 3rem;
        background: linear-gradient(145deg, #1e293b, #475569);
    }
    .prod-detail-hero .hero-title {
        position: absolute; left: 0; right: 0; bottom: 0;
        padding: 1.25rem 1.25rem 1rem;
        background: linear-gradient(transparent, rgba(15,23,42,0.92));
        color: #fff;
    }
    .prod-detail-hero .hero-title h4 { margin: 0; font-weight: 800; font-size: 1.25rem;}
    .prod-detail-body { padding: 1.25rem 1.35rem 0.5rem; }
    .prod-detail-row {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: 0.65rem 0;
        border-bottom: 1px solid #eef2f7;
        gap: 1rem;
    }
    .prod-detail-row:last-child { border-bottom: none; }
    .prod-detail-label {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.04em; color: #94a3b8; min-width: 110px;
    }
    .prod-detail-value {
        text-align: right; color: #0f172a; font-weight: 600; font-size: 0.95rem;
        flex: 1;
    }
    .prod-detail-value.desc {
        text-align: left; font-weight: 500; color: #334155; margin-top: 0.25rem;
        white-space: pre-wrap;
    }
</style>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            @if ($sucursales->count() > 1)
                <form method="GET" class="form-inline">
                    <label class="mr-2 mb-0 text-muted">{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">{{ __('Todas') }}</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ request('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('Nuevo producto') }}
        </a>
    </div>

    <div class="row libro-grid">
        @forelse ($productos as $producto)
            @php
                $existencia = $producto->inventario->cantidad ?? 0;
                $stockMin = $producto->inventario->stock_minimo ?? 0;
                $bajo = $producto->inventario && $existencia <= $stockMin;
                $img = $producto->imagen ? asset('portadas/'.$producto->imagen) : null;
            @endphp
            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                <div class="libro-card"
                     data-toggle="modal"
                     data-target="#prodDetailModal"
                     data-nombre="{{ $producto->nombre }}"
                     data-descripcion="{{ $producto->descripcion }}"
                     data-codigo="{{ $producto->codigo }}"
                     data-precio="{{ number_format($producto->precio, 2) }}"
                     data-categoria="{{ $producto->categoria->nombre ?? '—' }}"
                     data-sucursal="{{ $producto->sucursal->nombre ?? '—' }}"
                     data-existencia="{{ $existencia }}"
                     data-stock-min="{{ $stockMin }}"
                     data-activo="{{ $producto->activo ? '1' : '0' }}"
                     data-img="{{ $img }}">
                    <div class="libro-portada">
                        <div class="libro-badges">
                            @if (! $producto->activo)
                                <span class="badge badge-secondary">{{ __('Inactivo') }}</span>
                            @else
                                <span></span>
                            @endif
                            @if ($bajo)
                                <span class="badge badge-danger">{{ __('Bajo stock') }}</span>
                            @endif
                        </div>
                        @if ($img)
                            <img src="{{ $img }}" alt="{{ $producto->nombre }}">
                        @else
                            <div class="placeholder"><i class="fas fa-book"></i></div>
                        @endif
                    </div>
                    <div class="libro-body">
                        <h3 class="libro-titulo" title="{{ $producto->nombre }}">{{ $producto->nombre }}</h3>
                        <div class="libro-meta">
                            {{ $producto->sucursal->nombre_traducido ?? '—' }}
                            @if ($producto->categoria)
                                · {{ $producto->categoria->nombre }}
                            @endif
                            <br>{{ __('Stock') }}: {{ $existencia }}
                        </div>
                        <div class="libro-precio">${{ number_format($producto->precio, 2) }}</div>
                        <div class="libro-actions" onclick="event.stopPropagation();">
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                  onsubmit="return confirm(@json(__('¿Eliminar este producto?')));">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center py-5 mb-0">
                    <i class="fas fa-book-open fa-2x text-muted mb-2 d-block"></i>
                    <span class="text-muted">{{ __('No hay productos registrados.') }}</span>
                </div>
            </div>
        @endforelse
    </div>

    @if ($productos->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $productos->links() }}
        </div>
    @endif

    {{-- Modal detalle producto --}}
    <div class="modal fade prod-detail-modal" id="prodDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="prod-detail-hero">
                    <img id="pd-img" src="" alt="" style="display:none;">
                    <div class="hero-ph" id="pd-ph"><i class="fas fa-book"></i></div>
                    <div class="hero-title"><h4 id="pd-nombre"></h4></div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                            style="position:absolute;top:10px;right:14px;opacity:.9;text-shadow:none;">
                        <span aria-hidden="true" style="font-size:1.6rem;">&times;</span>
                    </button>
                </div>
                <div class="prod-detail-body">
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Código / SKU') }}</span>
                        <span class="prod-detail-value" id="pd-codigo">—</span>
                    </div>
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Categoría') }}</span>
                        <span class="prod-detail-value" id="pd-categoria">—</span>
                    </div>
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Sucursal') }}</span>
                        <span class="prod-detail-value" id="pd-sucursal">—</span>
                    </div>
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Precio') }}</span>
                        <span class="prod-detail-value" id="pd-precio">—</span>
                    </div>
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Existencia') }}</span>
                        <span class="prod-detail-value" id="pd-existencia">—</span>
                    </div>
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Stock mínimo') }}</span>
                        <span class="prod-detail-value" id="pd-stock-min">—</span>
                    </div>
                    <div class="prod-detail-row">
                        <span class="prod-detail-label">{{ __('Estado') }}</span>
                        <span class="prod-detail-value" id="pd-activo">—</span>
                    </div>
                    <div class="prod-detail-row" style="flex-direction:column;align-items:stretch;">
                        <span class="prod-detail-label">{{ __('Descripción') }}</span>
                        <span class="prod-detail-value desc" id="pd-descripcion">—</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cerrar') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
(function () {
    $('#prodDetailModal').on('show.bs.modal', function (event) {
        var btn = $(event.relatedTarget);
        var img = btn.data('img');
        var $img = $('#pd-img');
        var $ph = $('#pd-ph');

        $('#pd-nombre').text(btn.data('nombre') || '');
        $('#pd-codigo').text(btn.data('codigo') || '—');
        $('#pd-categoria').text(btn.data('categoria') || '—');
        $('#pd-sucursal').text(btn.data('sucursal') || '—');
        $('#pd-precio').text('$' + (btn.data('precio') || '0.00'));
        $('#pd-existencia').text(btn.data('existencia'));
        $('#pd-stock-min').text(btn.data('stock-min'));
        $('#pd-activo').html(
            btn.data('activo') == '1'
                ? '<span class="badge badge-success">{{ __('Activo') }}</span>'
                : '<span class="badge badge-secondary">{{ __('Inactivo') }}</span>'
        );
        var desc = btn.data('descripcion');
        $('#pd-descripcion').text(desc && String(desc).trim() !== '' ? desc : '—');

        if (img) {
            $img.attr('src', img).show();
            $ph.hide();
        } else {
            $img.hide().attr('src', '');
            $ph.show();
        }
    });
})();
</script>
@stop
