@extends('adminlte::page')

@section('title', __('Editar producto'))

@section('content_header')
    <h1>{{ __('Editar producto') }}</h1>
@stop

@section('css')
<style>
    .prod-form-card { border: none; border-radius: 12px; box-shadow: 0 2px 14px rgba(29, 45, 44,.07); }
    .prod-form-card .card-body { padding: 1.5rem 1.6rem; }
    .cat-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.5rem; max-height: 220px; overflow-y: auto; padding: 0.75rem;
        border: 1px solid var(--ui-border); border-radius: 10px; background: var(--ui-surface-2);
    }
    .cat-chip {
        display: flex; align-items: center; gap: 0.45rem; margin: 0; padding: 0.45rem 0.65rem;
        background: var(--ui-inset); border: 1px solid var(--ui-border); border-radius: 8px; cursor: pointer;
        font-size: 0.88rem; font-weight: 500; color: var(--ui-body); user-select: none;
    }
    .cat-chip.is-on { border-color: #2a6d6c; background: var(--ui-chip-on); color: var(--ui-title); box-shadow: 0 0 0 1px #2a6d6c inset; }
    .cat-chip input { margin: 0; }
    .cat-counter { font-size: 0.8rem; color: var(--ui-muted); }
    .cat-counter.warn { color: var(--ui-danger); font-weight: 600; }
    .cover-upload {
        display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
        padding: 1rem; border: 1px dashed var(--ui-border); border-radius: 12px; background: var(--ui-surface-2);
    }
    .cover-preview {
        width: 110px; height: 160px; border-radius: 8px; overflow: hidden;
        background: linear-gradient(145deg, #1d2d2c, #4a5a59);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.4); font-size: 1.8rem; flex-shrink: 0;
    }
    .cover-preview img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }
</style>
@stop

@section('content')
<div class="card prod-form-card">
    <div class="card-body">
        <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control" required>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ old('sucursal_id', $producto->sucursal_id) == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>{{ __('Estado') }}</label>
                    <select name="activo" class="form-control">
                        <option value="1" {{ old('activo', $producto->activo) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                        <option value="0" {{ old('activo', $producto->activo) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Nombre del libro') }}</label>
                <input type="text" name="nombre" maxlength="120" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>{{ __('Autor(es)') }} <small class="text-muted">({{ __('opcional') }})</small></label>
                    <input type="text" name="autor" maxlength="120" class="form-control" value="{{ old('autor', $producto->autor) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>{{ __('Editorial') }} <small class="text-muted">({{ __('opcional') }})</small></label>
                    <input type="text" name="editorial" maxlength="120" class="form-control" value="{{ old('editorial', $producto->editorial) }}">
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Descripción') }}</label>
                <textarea name="descripcion" rows="2" maxlength="30" class="form-control">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>{{ __('Código / SKU') }}</label>
                    <input type="text" name="codigo" maxlength="40" class="form-control" value="{{ old('codigo', $producto->codigo) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>{{ __('Categoría') }}</label>
                    <select name="categoria_id" class="form-control">
                        <option value="">{{ __('— Sin categoría —') }}</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>{{ __('Precio') }}</label>
                    <input type="number" step="0.01" min="0" name="precio" max="999999.99" class="form-control" value="{{ old('precio', $producto->precio) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>{{ __('Existencia (stock)') }}</label>
                    <input type="number" min="0" name="cantidad" max="100000" class="form-control"
                           value="{{ old('cantidad', $producto->inventario->cantidad ?? 0) }}" required>
                    <small class="form-text text-muted">{{ __('Admin y Gerente pueden ajustar la cantidad cuando quieran.') }}</small>
                </div>
                <div class="form-group col-md-6">
                    <label>{{ __('Stock mínimo (alerta)') }}</label>
                    <input type="number" min="0" name="stock_minimo" max="100000" class="form-control"
                           value="{{ old('stock_minimo', $producto->inventario->stock_minimo ?? 5) }}" required>
                </div>
            </div>

            @php $selected = old('genero_ids', $producto->generos->pluck('id')->all()); @endphp
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="mb-0">{{ __('Géneros') }} <small class="text-muted">(1 a 5)</small></label>
                    <span class="cat-counter" id="cat-counter">0 / 5</span>
                </div>
                <div class="cat-grid">
                    @forelse ($generos as $g)
                        <label class="cat-chip">
                            <input type="checkbox" name="genero_ids[]" value="{{ $g->id }}" class="cat-check"
                                   {{ in_array($g->id, $selected) ? 'checked' : '' }}>
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
                    <div class="cover-preview">
                        @if ($producto->imagen)
                            <img src="{{ asset('portadas/'.$producto->imagen) }}" alt="" id="cover-img">
                            <i class="fas fa-book" id="cover-icon" style="display:none;"></i>
                        @else
                            <i class="fas fa-book" id="cover-icon"></i>
                            <img src="" alt="" id="cover-img" style="display:none;">
                        @endif
                    </div>
                    <div>
                        <input type="file" name="imagen" id="input-imagen" accept="image/*" class="d-none">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-pick-cover">
                            <i class="fas fa-image"></i> {{ __('Cambiar imagen') }}
                        </button>
                        @if ($producto->imagen)
                            <div class="custom-control custom-checkbox d-inline-block ml-2">
                                <input type="checkbox" class="custom-control-input" id="quitar_imagen" name="quitar_imagen" value="1">
                                <label class="custom-control-label" for="quitar_imagen">{{ __('Quitar imagen') }}</label>
                            </div>
                        @endif
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
        img.src = URL.createObjectURL(input.files[0]); img.style.display = 'block'; if (icon) icon.style.display = 'none';
    });
})();
</script>
@stop
