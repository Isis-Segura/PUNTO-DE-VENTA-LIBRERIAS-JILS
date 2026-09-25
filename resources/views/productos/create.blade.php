@extends('adminlte::page')

@section('title', __('Nuevo producto'))

@section('content_header')
    <h1>{{ __('Nuevo producto') }}</h1>
@stop

@section('css')
<style>
    .prod-form-card { border: none; border-radius: 12px; box-shadow: 0 2px 14px rgba(15,23,42,.07); }
    .prod-form-card .card-body { padding: 1.5rem 1.6rem; }
    .cat-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.5rem; max-height: 220px; overflow-y: auto; padding: 0.75rem;
        border: 1px solid #e2e8f0; border-radius: 10px; background: #f8fafc;
    }
    .cat-chip {
        display: flex; align-items: center; gap: 0.45rem; margin: 0; padding: 0.45rem 0.65rem;
        background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer;
        font-size: 0.88rem; font-weight: 500; color: #334155; user-select: none;
    }
    .cat-chip.is-on { border-color: #4f46e5; background: #eef2ff; color: #3730a3; box-shadow: 0 0 0 1px #4f46e5 inset; }
    .cat-chip input { margin: 0; }
    .cat-counter { font-size: 0.8rem; color: #64748b; }
    .cat-counter.warn { color: #dc2626; font-weight: 600; }
    .cover-upload {
        display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
        padding: 1rem; border: 1px dashed #cbd5e1; border-radius: 12px; background: #f8fafc;
    }
    .cover-preview {
        width: 110px; height: 160px; border-radius: 8px; overflow: hidden;
        background: linear-gradient(145deg, #1e293b, #475569);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.4); font-size: 1.8rem; flex-shrink: 0;
    }
    .cover-preview img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }
</style>
@stop

@section('content')
<div class="card prod-form-card">
    <div class="card-body">
        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control" required>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ old('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>{{ __('Estado') }}</label>
                    <select name="activo" class="form-control">
                        <option value="1" selected>{{ __('Activo') }}</option>
                        <option value="0">{{ __('Inactivo') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Nombre del libro') }}</label>
                <input type="text" name="nombre" maxlength="120" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                @error('nombre')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>{{ __('Autor(es)') }} <small class="text-muted">({{ __('opcional') }})</small></label>
                    <input type="text" name="autor" maxlength="120" class="form-control" value="{{ old('autor') }}" placeholder="{{ __('Ej. Rumiko Takahashi') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>{{ __('Editorial') }} <small class="text-muted">({{ __('opcional') }})</small></label>
                    <input type="text" name="editorial" maxlength="120" class="form-control" value="{{ old('editorial') }}" placeholder="{{ __('Ej. Panini') }}">
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Descripción') }}</label>
                <textarea name="descripcion" rows="3" maxlength="2000" class="form-control">{{ old('descripcion') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>{{ __('Código / SKU') }}</label>
                    <input type="text" name="codigo" maxlength="40" class="form-control" value="{{ old('codigo') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>{{ __('Categoría') }}</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">{{ __('— Sin categoría —') }}</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">{{ __('Una sola categoría (como manga, novela, etc.).') }}</small>
                </div>
                <div class="form-group col-md-4">
                    <label>{{ __('Precio') }}</label>
                    <input type="number" step="0.01" min="0" name="precio" max="999999.99" class="form-control" value="{{ old('precio') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>{{ __('Cantidad inicial en inventario') }}</label>
                    <input type="number" min="0" name="cantidad_inicial" max="100000" class="form-control" value="{{ old('cantidad_inicial', 0) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>{{ __('Stock mínimo (alerta)') }}</label>
                    <input type="number" min="0" name="stock_minimo" max="100000" class="form-control" value="{{ old('stock_minimo', 5) }}" required>
                </div>
            </div>

            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="mb-0">{{ __('Géneros') }} <small class="text-muted">(1 a 5)</small></label>
                    <span class="cat-counter" id="cat-counter">0 / 5</span>
                </div>
                <div class="cat-grid">
                    @forelse ($generos as $g)
                        <label class="cat-chip">
                            <input type="checkbox" name="genero_ids[]" value="{{ $g->id }}" class="cat-check"
                                   {{ in_array($g->id, old('genero_ids', [])) ? 'checked' : '' }}>
                            <span>{{ $g->nombre_traducido }}</span>
                        </label>
                    @empty
                        <span class="text-muted">{{ __('No hay géneros. Créalos en el menú Géneros.') }}</span>
                    @endforelse
                </div>
                @error('genero_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>{{ __('Portada del libro') }}</label>
                <div class="cover-upload">
                    <div class="cover-preview" id="cover-preview">
                        <i class="fas fa-book" id="cover-icon"></i>
                        <img src="" alt="" id="cover-img" style="display:none;">
                    </div>
                    <div>
                        <input type="file" name="imagen" id="input-imagen" accept="image/*" class="d-none">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-pick-cover">
                            <i class="fas fa-image"></i> {{ __('Elegir imagen') }}
                        </button>
                        <div class="small text-muted mt-2">{{ __('JPG, PNG o WEBP. Máx. 4 MB.') }}</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
(function () {
    var checks = document.querySelectorAll('.cat-check'), counter = document.getElementById('cat-counter'), max = 5;
    function refresh() {
        var n = 0;
        checks.forEach(function (c) {
            var chip = c.closest('.cat-chip');
            if (c.checked) { n++; chip.classList.add('is-on'); } else chip.classList.remove('is-on');
        });
        counter.textContent = n + ' / ' + max;
        counter.classList.toggle('warn', n > max || n < 1);
        checks.forEach(function (c) { if (!c.checked) c.disabled = n >= max; });
    }
    checks.forEach(function (c) {
        c.addEventListener('change', function () {
            if (document.querySelectorAll('.cat-check:checked').length > max) c.checked = false;
            refresh();
        });
    });
    refresh();
    var input = document.getElementById('input-imagen'), img = document.getElementById('cover-img'), icon = document.getElementById('cover-icon');
    document.getElementById('btn-pick-cover').addEventListener('click', function () { input.click(); });
    input.addEventListener('change', function () {
        if (!input.files[0]) return;
        img.src = URL.createObjectURL(input.files[0]); img.style.display = 'block'; icon.style.display = 'none';
    });
})();
</script>
@stop
