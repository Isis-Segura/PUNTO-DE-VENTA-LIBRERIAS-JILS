@php
    $routeName = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
    $action = null;
    $placeholder = __('Buscar…');

    if (\Illuminate\Support\Str::startsWith($routeName, 'productos')) {
        $action = route('productos.index');
        $placeholder = __('Buscar producto (nombre o SKU)…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'sucursales')) {
        $action = route('sucursales.index');
        $placeholder = __('Buscar sucursal…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'admin.usuarios') || \Illuminate\Support\Str::startsWith($routeName, 'usuarios')) {
        $action = route('admin.usuarios.index');
        $placeholder = __('Buscar usuario (nombre o correo)…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'categorias')) {
        $action = route('categorias.index');
        $placeholder = __('Buscar categoría…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'generos')) {
        $action = route('generos.index');
        $placeholder = __('Buscar género…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'ventas') && ! \Illuminate\Support\Str::contains($routeName, 'create') && ! \Illuminate\Support\Str::contains($routeName, 'pos')) {
        $action = route('ventas.index');
        $placeholder = __('Buscar venta (folio)…');
    }
    // Dashboard (home, admin.index) y POS: $action queda null → no se abre nada
@endphp

<li class="nav-item d-none d-sm-inline-block">
    @if ($action)
        <form action="{{ $action }}" method="GET" class="form-inline ml-2 mr-2" style="min-width: 220px;" onsubmit="return true;">
            @foreach (request()->except('q', 'page', 'adminlteSearch', '_token') as $key => $val)
                @if (is_scalar($val))
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach
            <div class="input-group input-group-sm">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control"
                       placeholder="{{ $placeholder }}" style="min-width: 180px;">
                <div class="input-group-append">
                    <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
                    @if (request('q'))
                        <a class="btn btn-default" href="{{ $action }}"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
        </form>
    @endif
</li>
