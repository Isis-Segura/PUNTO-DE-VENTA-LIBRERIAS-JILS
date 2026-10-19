@php
    $routeName = \Illuminate\Support\Facades\Route::currentRouteName() ?? '';
    $action = null;
    $placeholder = __('Buscar…');

    if (\Illuminate\Support\Str::startsWith($routeName, 'productos')) {
        $action = route('productos.index');
        $placeholder = __('Nombre o SKU…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'sucursales')) {
        $action = route('sucursales.index');
        $placeholder = __('Nombre o dirección…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'admin.usuarios') || \Illuminate\Support\Str::startsWith($routeName, 'usuarios')) {
        $action = route('admin.usuarios.index');
        $placeholder = __('Nombre o correo…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'categorias')) {
        $action = route('categorias.index');
        $placeholder = __('Nombre…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'generos')) {
        $action = route('generos.index');
        $placeholder = __('Nombre…');
    } elseif (\Illuminate\Support\Str::startsWith($routeName, 'ventas') && ! \Illuminate\Support\Str::contains($routeName, 'create') && ! \Illuminate\Support\Str::contains($routeName, 'pos')) {
        $action = route('ventas.index');
        $placeholder = __('Folio…');
    }
@endphp

<li class="nav-item">
    @if ($action)
        <form action="{{ $action }}" method="GET" class="form-inline my-2 px-2">
            @foreach (request()->except('q', 'page', 'adminlteSearch', '_token') as $key => $val)
                @if (is_scalar($val))
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach
            <div class="input-group">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sidebar" placeholder="{{ $placeholder }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-sidebar"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
    @endif
</li>
