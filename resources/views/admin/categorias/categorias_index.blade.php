@extends('adminlte::page')

@section('title', __('Categorías'))

@section('content_header')
    <h1>{{ __('Categorías') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card pdv-scroll-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ __('Listado de categorías') }}</span>
            <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nueva categoría') }}
            </a>
        </div>

        <div class="card-body p-0 pdv-table-scroll">
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
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion ?? '—' }}</td>
                            <td>{{ $categoria->productos_count }}</td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('¿Eliminar esta categoría?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">{{ __('No hay categorías registradas.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categorias->hasPages())
            <div class="card-footer clearfix">
                {{ $categorias->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    /* La tabla hace scroll interno; el menú y el encabezado no se estiran */
    .pdv-table-scroll {
        max-height: calc(100vh - 280px);
        overflow-y: auto;
        overflow-x: auto;
    }
    .pdv-table-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8f9fa;
        box-shadow: 0 1px 0 #dee2e6;
    }
    .pdv-table-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .pdv-table-scroll::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 8px;
    }
    .pdv-table-scroll::-webkit-scrollbar-track {
        background: #e2e8f0;
    }
</style>
@stop
