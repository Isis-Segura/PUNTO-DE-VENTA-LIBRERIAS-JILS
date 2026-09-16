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
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.45);
        font-size: 2.8rem;
    }
    .libro-body {
        padding: 0.85rem 0.95rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .libro-titulo {
        font-weight: 700;
        font-size: 0.92rem;
        line-height: 1.3;
        margin: 0 0 0.35rem;
        color: #0f172a;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4em;
    }
    .libro-meta {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.45rem;
    }
    .libro-precio {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: auto;
    }
    .libro-badges {
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        display: flex;
        justify-content: space-between;
        gap: 4px;
        z-index: 1;
    }
    .libro-actions {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.6rem;
    }
    .libro-actions form { flex: 1; margin: 0; }
    .libro-actions .btn { width: 100%; }
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
                $bajo = $producto->inventario && $existencia <= ($producto->inventario->stock_minimo ?? 0);
                $img = $producto->imagen
                    ? asset('storage/'.$producto->imagen)
                    : null;
            @endphp
            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                <div class="libro-card">
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
                            {{ $producto->sucursal->nombre ?? '—' }}
                            @if ($producto->categoria)
                                · {{ $producto->categoria->nombre }}
                            @endif
                            <br>{{ __('Stock') }}: {{ $existencia }}
                        </div>
                        <div class="libro-precio">${{ number_format($producto->precio, 2) }}</div>
                        <div class="libro-actions">
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este producto?');">
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
@stop
