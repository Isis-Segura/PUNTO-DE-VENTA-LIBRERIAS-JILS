@extends('adminlte::page')

@section('title', __('Categorías'))

@section('content_header')
    <h1>{{ __('Categorías') }}</h1>
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Listado de categorías') }}</span>
            <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nueva categoría') }}
            </a>
        </div>

        <div id="js-resultados">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Descripción') }}</th>
                        <th>{{ __('Productos') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $categoria)
                        <tr class="js-search-item" data-search="{{ strtolower(($categoria->nombre??'').' '.($categoria->descripcion??'')) }}">
                            <td>{{ $categoria->nombre_traducido }}</td>
                            <td>{{ $categoria->descripcion_traducida ?: '—' }}</td>
                            <td>{{ $categoria->productos_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-warning" data-confirm="{{ __('¿Deseas editar este registro?') }}" data-confirm-title="{{ __('Confirmar edición') }}" data-confirm-type="warning" data-confirm-ok="{{ __('Sí, editar') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline" data-confirm="{{ __('¿Eliminar esta categoría?') }}" data-confirm-title="{{ __('¿Eliminar?') }}" data-confirm-type="danger" data-confirm-ok="{{ __('Sí, eliminar') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        @if (request()->filled('q') || request()->filled('adminlteSearch'))
                            <tr >
                            <td colspan="99" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i> {{ __('No existe ninguna categoría con esa búsqueda.') }}
                            </td>
                        </tr>
                        @else
                            <tr>
                            <td colspan="4" class="text-center py-3">{{ __('No hay categorías registradas.') }}</td>
                        </tr>
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $categorias->links() }}
        </div>
    </div>
    </div>
@stop


@section('js')
    @include('partials.busqueda-servidor')
@stop
