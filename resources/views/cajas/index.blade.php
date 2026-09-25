@extends('adminlte::page')

@section('title', __('Cajas'))

@section('content_header')
    <h1>{{ __('Cajas') }}</h1>
@stop

@section('content')
    @once
        @include('partials.app-confirm-modal')
    @endonce

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="gap: .5rem;">
            <span>{{ __('Listado de cajas') }}</span>
            <div class="d-flex align-items-center flex-wrap" style="gap: .5rem;">
                <form method="GET" action="{{ route('cajas.index') }}" class="form-inline" id="form-buscar-cajas">
                    <div class="input-group input-group-sm">
                        <input type="search"
                               name="q"
                               id="buscador-cajas"
                               value="{{ request('q', request('adminlteSearch')) }}"
                               class="form-control"
                               placeholder="{{ __('Buscar por nombre o sucursal...') }}"
                               style="min-width: 220px;">
                        <div class="input-group-append">
                            <button class="btn btn-default" type="submit" title="{{ __('Buscar') }}">
                                <i class="fas fa-search"></i>
                            </button>
                            @if (request()->filled('q') || request()->filled('adminlteSearch'))
                                <a href="{{ route('cajas.index') }}" class="btn btn-default" title="{{ __('Limpiar') }}">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
                <a href="{{ route('cajas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ __('Nueva caja') }}
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0" id="tabla-cajas">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Descripción') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cajas as $caja)
                        @php
                            $search = strtolower(implode(' ', array_filter([
                                $caja->nombre ?? '',
                                $caja->nombre_traducido ?? '',
                                $caja->descripcion ?? '',
                                $caja->sucursal->nombre ?? '',
                                $caja->sucursal->nombre_traducido ?? '',
                                $caja->activa ? __('Activa') : __('Inactiva'),
                            ])));
                        @endphp
                        <tr class="js-search-item" data-search="{{ $search }}">
                            <td>{{ $caja->nombre_traducido }}</td>
                            <td>{{ $caja->sucursal->nombre_traducido ?? ($caja->sucursal->nombre ?? '—') }}</td>
                            <td>{{ $caja->descripcion ?? '—' }}</td>
                            <td>
                                @if ($caja->activa)
                                    <span class="badge badge-success">{{ __('Activa') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactiva') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('cajas.edit', $caja) }}"
                                   class="btn btn-sm btn-warning"
                                   data-confirm="{{ __('¿Deseas editar este registro?') }}"
                                   data-confirm-title="{{ __('Confirmar edición') }}"
                                   data-confirm-type="warning"
                                   data-confirm-ok="{{ __('Sí, editar') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('cajas.destroy', $caja) }}" method="POST" class="d-inline"
                                      data-confirm="{{ __('¿Eliminar esta caja?') }}"
                                      data-confirm-title="{{ __('¿Eliminar?') }}"
                                      data-confirm-type="danger"
                                      data-confirm-ok="{{ __('Sí, eliminar') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="fila-sin-resultados-servidor">
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('No hay cajas registradas.') }}
                            </td>
                        </tr>
                    @endforelse
                    <tr id="fila-sin-coincidencias" style="display: none;">
                        <td colspan="5" class="text-center text-muted py-4">
                            {{ __('No se encontraron cajas con ese criterio.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($cajas->hasPages())
            <div class="card-footer">
                {{ $cajas->links() }}
            </div>
        @endif
    </div>
@stop

@section('js')
<script>
(function () {
    var input = document.getElementById('buscador-cajas');
    var rows = document.querySelectorAll('#tabla-cajas tbody tr.js-search-item');
    var emptyRow = document.getElementById('fila-sin-coincidencias');
    if (!input || !rows.length) return;

    function filtrar() {
        var q = (input.value || '').trim().toLowerCase();
        var visibles = 0;
        rows.forEach(function (tr) {
            var hay = !q || (tr.getAttribute('data-search') || '').indexOf(q) !== -1;
            tr.style.display = hay ? '' : 'none';
            if (hay) visibles++;
        });
        if (emptyRow) {
            emptyRow.style.display = visibles === 0 ? '' : 'none';
        }
    }

    input.addEventListener('input', filtrar);
    // Si viene con valor del servidor, aplicar filtro visual también
    if (input.value) filtrar();
})();
</script>
@stop
