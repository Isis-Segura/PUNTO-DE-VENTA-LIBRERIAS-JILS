@extends('adminlte::page')

@section('title', __('Bajas definitivas'))

@section('content_header')
    <h1>{{ __('Bajas definitivas') }}</h1>
@stop

@section('css')
<style>
    .table-bajas th,
    .table-bajas td { vertical-align: middle; padding: .9rem 1rem; }
    .table-bajas .hora { font-size: .8rem; }
</style>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center">
            <span class="mr-auto">{{ __('Usuarios eliminados de forma permanente') }}</span>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('Volver a usuarios') }}
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-striped table-bajas mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Usuario') }}</th>
                        <th>{{ __('Rol') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Ingreso') }}</th>
                        <th>{{ __('Se puso inactivo') }}</th>
                        <th>{{ __('Regresó') }}</th>
                        <th>{{ __('Salida definitiva') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bajas as $baja)
                        @php
                            $columnas = [
                                $baja->ingreso_at,
                                $baja->desactivado_at,
                                $baja->reactivado_at,
                                $baja->eliminado_at,
                            ];
                            $textoBusqueda = $baja->name.' '.$baja->email.' '.$baja->rol_nombre.' '.$baja->sucursal_nombre.' '
                                .collect($columnas)->map(fn ($f) => \App\Models\User::formatearFecha($f))->filter()->implode(' ');
                        @endphp
                        <tr class="js-search-item" data-search="{{ strtolower($textoBusqueda) }}">
                            <td>
                                <div class="font-weight-bold">{{ $baja->name }}</div>
                                <div class="text-muted" style="font-size:.85rem;">{{ $baja->email }}</div>
                            </td>
                            <td>{{ $baja->rol_nombre }}</td>
                            <td>{{ $baja->sucursal_nombre }}</td>
                            {{-- Si una fecha no existe, la celda queda vacía. --}}
                            @foreach ($columnas as $fecha)
                                <td>
                                    @if ($fecha)
                                        <div>{{ \Illuminate\Support\Carbon::parse($fecha)->timezone(\App\Models\User::ZONA_HORARIA_VISTA)->format('d/m/Y') }}</div>
                                        <div class="hora text-muted">{{ \Illuminate\Support\Carbon::parse($fecha)->timezone(\App\Models\User::ZONA_HORARIA_VISTA)->format('H:i') }}</div>
                                    @endif
                                    @if ($loop->last && $baja->eliminado_por)
                                        <div class="hora text-muted">{{ __('Eliminado por') }}: {{ $baja->eliminado_por }}</div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ __('Aún no hay bajas definitivas.') }}</td>
                        </tr>
                    @endforelse
                    @if ($bajas->count())
                        <tr class="js-search-empty" style="display:none;">
                            <td colspan="99" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i> {{ __('No existe ningún usuario con esa búsqueda.') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $bajas->links() }}
        </div>
    </div>
@stop

@section('js')
@include('partials.tabla-buscador')
@stop
