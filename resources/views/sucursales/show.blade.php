@extends('adminlte::page')

@section('title', __('Sucursal').' - '.$sucursal->nombre_traducido)

@section('content_header')
    <h1>{{ $sucursal->nombre_traducido }}</h1>
@stop

@section('css')
<style>
    .libro-grid { margin-left: -8px; margin-right: -8px; }
    .libro-grid > [class*="col-"] { padding-left: 8px; padding-right: 8px; }
    .libro-card {
        border: none; border-radius: 12px; overflow: hidden;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.08);
        transition: transform .15s ease, box-shadow .15s ease;
        height: 100%; background: #fff; display: flex; flex-direction: column; cursor: pointer;
    }
    .libro-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14); }
    .libro-portada {
        position: relative; width: 100%; aspect-ratio: 2 / 3;
        background: linear-gradient(145deg, #1e293b, #475569); overflow: hidden;
    }
    .libro-portada img { width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; }
    .libro-portada .placeholder {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.45); font-size: 2.5rem;
    }
    .libro-body { padding: 0.75rem 0.85rem 0.9rem; flex: 1; display: flex; flex-direction: column; }
    .libro-titulo {
        font-weight: 700; font-size: 0.88rem; line-height: 1.3; margin: 0 0 0.3rem; color: #0f172a;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.3em;
    }
    .libro-meta { font-size: 0.72rem; color: #64748b; margin-bottom: 0.35rem; }
    .libro-precio { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-top: auto; }
    .libro-badges { position: absolute; top: 8px; left: 8px; right: 8px; display: flex; justify-content: space-between; z-index: 1; }
    .libro-actions { margin-top: 0.6rem; display: flex; gap: 0.4rem; }
    .libro-actions form { flex: 1; margin: 0; }
    .libro-actions .btn { width: 100%; }

    .prod-detail-modal .modal-dialog { max-width: 720px; }
    .prod-detail-layout { display: flex; flex-wrap: wrap; min-height: 320px; }
    .prod-detail-cover {
        flex: 0 0 42%; max-width: 42%; background: #0f172a; position: relative; min-height: 360px;
    }
    .prod-detail-cover img {
        width: 100%; height: 100%; object-fit: cover; object-position: center top;
        position: absolute; inset: 0; filter: brightness(0.88) saturate(0.95);
    }
    .prod-detail-cover .cover-ph {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.35); font-size: 3.5rem;
        background: linear-gradient(160deg, #1e293b, #334155);
    }
    .prod-detail-cover .cover-fade {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.05) 30%, rgba(0,0,0,0.55) 70%, rgba(0,0,0,0.82) 100%);
        pointer-events: none;
    }
    .prod-detail-cover .cover-title {
        position: absolute; left: 0; right: 0; bottom: 0; padding: 1.2rem 1.1rem; color: #fff; z-index: 2;
    }
    .prod-detail-cover .cover-title h4 {
        margin: 0; font-weight: 800; font-size: 1.2rem; line-height: 1.3;
        text-shadow: 0 1px 2px rgba(0,0,0,.9), 0 2px 12px rgba(0,0,0,.7);
        color: #fff;
        letter-spacing: 0.01em;
    }
    .prod-detail-info { flex: 1; min-width: 260px; padding: 1.25rem 1.4rem 0.75rem; background: #fff; }
    .prod-detail-row {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: 0.7rem 0; border-bottom: 1px solid #eef2f7; gap: 1rem;
    }
    .prod-detail-label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.05em; color: #94a3b8; min-width: 100px; padding-top: 2px;
    }
    .prod-detail-value { text-align: right; color: #0f172a; font-weight: 600; font-size: 0.95rem; flex: 1; }
    .prod-detail-value.desc { text-align: left; font-weight: 500; color: #334155; margin-top: 0.2rem; white-space: pre-wrap; }
    @media (max-width: 575.98px) {
        .prod-detail-cover { flex: 0 0 100%; max-width: 100%; min-height: 220px; }
    }
</style>
@stop

@section('content')

    @once
        @include('partials.app-confirm-modal')
    @endonce

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('sucursales.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('Volver a sucursales') }}
        </a>
    </div>

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
                    <strong>{{ __('Dirección') }}:</strong><br>{{ $sucursal->direccion ?: '-' }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('Teléfono') }}:</strong><br>{{ $sucursal->telefono ?: '-' }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('Gerente') }}:</strong><br>{{ $sucursal->gerente->name ?? __('Sin asignar') }}
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

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <span class="mb-1">{{ __('Inventario') }}</span>
                <form method="GET" class="form-inline mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="bajo_stock" name="bajo_stock" value="1"
                               {{ request('bajo_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="custom-control-label" for="bajo_stock">{{ __('Solo mostrar bajo inventario') }}</label>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row libro-grid">
                @forelse ($inventarios as $inv)
                    @php
                        $p = $inv->producto;
                        if (! $p) continue;
                        $existencia = $inv->cantidad;
                        $bajo = $existencia <= $inv->stock_minimo;
                        $img = $p->imagen ? asset('storage/'.$p->imagen) : null;
                        $payload = [
                            'nombre' => $p->nombre,
                            'descripcion' => $p->descripcion ?? '',
                            'codigo' => $p->codigo ?? '',
                            'precio' => number_format($p->precio, 2),
                            'categorias' => $p->generos_lista,
                            'sucursal' => $sucursal->nombre_traducido,
                            'existencia' => $existencia,
                            'stockMin' => $inv->stock_minimo,
                            'activo' => $p->activo ? 1 : 0,
                            'img' => $img,
                        ];
                    @endphp
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                        <div class="libro-card js-prod-detail" data-prod='@json($payload)'>
                            <div class="libro-portada">
                                <div class="libro-badges">
                                    @if ($bajo)
                                        <span class="badge badge-danger">{{ __('Bajo stock') }}</span>
                                    @else
                                        <span class="badge badge-success">OK</span>
                                    @endif
                                </div>
                                @if ($img)
                                    <img src="{{ $img }}" alt="{{ $p->nombre }}">
                                @else
                                    <div class="placeholder"><i class="fas fa-book"></i></div>
                                @endif
                            </div>
                            <div class="libro-body">
                                <h3 class="libro-titulo">{{ $p->nombre }}</h3>
                                <div class="libro-meta">
                                    {{ $p->generos_lista }}
                                    <br>{{ __('Stock') }}: {{ $existencia }} / mín. {{ $inv->stock_minimo }}
                                </div>
                                <div class="libro-precio">${{ number_format($p->precio, 2) }}</div>
                                <div class="libro-actions" onclick="event.stopPropagation();">
                                    <a href="{{ route('productos.edit', $p) }}" class="btn btn-sm btn-warning"
                                       data-confirm="{{ __('¿Deseas editar este registro?') }}"
                                       data-confirm-title="{{ __('Confirmar edición') }}"
                                       data-confirm-type="warning"
                                       data-confirm-ok="{{ __('Sí, editar') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('productos.destroy', $p) }}" method="POST"
                                          data-confirm="{{ __('¿Eliminar este producto?') }}"
                                          data-confirm-title="{{ __('¿Eliminar?') }}"
                                          data-confirm-type="danger"
                                          data-confirm-ok="{{ __('Sí, eliminar') }}">
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
                    <div class="col-12 text-center text-muted py-4">
                        {{ __('Esta sucursal no tiene productos en inventario.') }}
                    </div>
                @endforelse
            </div>
        </div>
        @if ($inventarios->hasPages())
            <div class="card-footer">{{ $inventarios->links() }}</div>
        @endif
    </div>

    <div class="modal fade prod-detail-modal" id="prodDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow overflow-hidden">
                <div class="prod-detail-layout">
                    <div class="prod-detail-cover">
                        <img id="pd-img" src="" alt="" style="display:none;">
                        <div class="cover-ph" id="pd-ph"><i class="fas fa-book"></i></div>
                        <div class="cover-fade"></div>
                        <div class="cover-title"><h4 id="pd-nombre"></h4></div>
                        <button type="button" class="close text-white" data-dismiss="modal"
                                style="position:absolute;top:10px;right:12px;z-index:3;opacity:.95;text-shadow:0 1px 4px #000;">
                            <span style="font-size:1.5rem;">&times;</span>
                        </button>
                    </div>
                    <div class="prod-detail-info">
                        <div class="prod-detail-row">
                            <span class="prod-detail-label">{{ __('Código / SKU') }}</span>
                            <span class="prod-detail-value" id="pd-codigo">—</span>
                        </div>
                        <div class="prod-detail-row">
                            <span class="prod-detail-label">{{ __('Categorías') }}</span>
                            <span class="prod-detail-value" id="pd-categorias">—</span>
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
                        <div class="text-right mt-2 mb-2">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('Cerrar') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
(function () {
    function fillModal(data) {
        $('#pd-nombre').text(data.nombre || '');
        $('#pd-codigo').text(data.codigo || '—');
        $('#pd-categorias').text(data.categorias || data.generos || '—');
        $('#pd-sucursal').text(data.sucursal || '—');
        $('#pd-precio').text('$' + (data.precio || '0.00'));
        $('#pd-existencia').text(data.existencia);
        $('#pd-stock-min').text(data.stockMin);
        $('#pd-activo').html(
            data.activo == 1
                ? '<span class="badge badge-success">{{ __('Activo') }}</span>'
                : '<span class="badge badge-secondary">{{ __('Inactivo') }}</span>'
        );
        var desc = (data.descripcion || '').trim();
        $('#pd-descripcion').text(desc !== '' ? desc : '—');
        if (data.img) {
            $('#pd-img').attr('src', data.img).show();
            $('#pd-ph').hide();
        } else {
            $('#pd-img').hide().attr('src', '');
            $('#pd-ph').show();
        }
        $('#prodDetailModal').modal('show');
    }
    $(document).on('click', '.js-prod-detail', function () {
        var raw = this.getAttribute('data-prod');
        if (!raw) return;
        try { fillModal(JSON.parse(raw)); } catch (e) { console.error(e); }
    });
})();
</script>
@stop
