@extends('adminlte::page')

@section('title', __('Usuarios'))

@section('content_header')
    <h1>{{ __('Usuarios') }}</h1>
@stop

@section('css')
<style>
    /* Tabla de usuarios: más aire entre columnas y acciones agrupadas para que no se vea amontonado */
    .table-usuarios th,
    .table-usuarios td { vertical-align: middle; padding: .9rem 1rem; }
    .table-usuarios .usuario-correo { font-size: .85rem; }
    .table-usuarios .acciones { display: flex; justify-content: flex-end; flex-wrap: nowrap; gap: .4rem; }
    .table-usuarios .acciones form { margin: 0; }
    .table-usuarios .acciones .btn { width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
    .fecha-icono { width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; flex: 0 0 34px; }
</style>
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
        <div class="card-header d-flex flex-wrap align-items-center">
            <span class="mr-auto">{{ __('Listado de usuarios') }}</span>
            <a href="{{ route('admin.usuarios.bajas') }}" class="btn btn-outline-secondary btn-sm mr-2">
                <i class="fas fa-user-times"></i> {{ __('Bajas definitivas') }}
            </a>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nuevo usuario') }}
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-striped table-usuarios mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Usuario') }}</th>
                        <th>{{ __('Rol') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th>{{ __('Fechas') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        @php
                            $aceptado = $usuario->haAceptadoTerminos();
                            $fechas = $usuario->fechasParaMostrar();
                            // Mismo criterio que la etiqueta de la columna Estado:
                            // "pendiente" mientras no acepte; al aceptar pasa a "activo".
                            $estado = ! $usuario->activo ? 'inactivo' : ($aceptado ? 'activo' : 'pendiente');
                            $textoBusqueda = ($usuario->name ?? '').' '.($usuario->email ?? '').' '
                                .($usuario->role->nombre_traducido ?? '').' '.($usuario->sucursal->nombre_traducido ?? '').' '
                                .collect($fechas)->pluck('fecha')->implode(' ');
                        @endphp
                        <tr class="js-search-item" data-estado="{{ $estado }}" data-search="{{ strtolower($textoBusqueda) }}">
                            <td>
                                <div class="font-weight-bold">{{ $usuario->name }}</div>
                                <div class="usuario-correo text-muted">{{ $usuario->email }}</div>
                            </td>
                            <td>{{ $usuario->role->nombre_traducido ?? __('Sin rol') }}</td>
                            <td>{{ $usuario->sucursal->nombre ?? __('—') }}</td>
                            <td>
                                {{-- Mientras no acepte los términos solo se ve "Pendiente"; al aceptar pasa a "Activo". --}}
                                @if ($estado === 'pendiente')
                                    <span class="badge badge-warning">{{ __('Pendiente de términos') }}</span>
                                @elseif ($estado === 'activo')
                                    <span class="badge badge-success">{{ __('Activo') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactivo') }}</span>
                                @endif
                            </td>
                            <td>
                                {{-- Solo aparece si ya hay alguna fecha que mostrar. --}}
                                @if (count($fechas))
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-toggle="modal" data-target="#modalFechas"
                                            data-nombre="{{ $usuario->name }}"
                                            data-fechas="{{ json_encode($fechas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}">
                                        <i class="far fa-calendar-alt"></i> {{ __('Ver') }}
                                    </button>
                                @endif
                            </td>
                            <td>
                                <div class="acciones">
                                    {{-- El reenvío solo existe mientras el usuario no haya aceptado los términos. --}}
                                    @unless ($aceptado)
                                        <form action="{{ route('admin.usuarios.reenviar-terminos', $usuario) }}" method="POST" data-confirm="{{ __('¿Reenviar el correo de bienvenida a este usuario?') }}" data-confirm-title="{{ __('Confirmar reenvío') }}" data-confirm-type="primary" data-confirm-ok="{{ __('Sí, reenviar') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info" title="{{ __('Reenviar correo de bienvenida') }}">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                    @endunless

                                    {{-- Solo para usuarios inactivos: vuelven a "Activo". --}}
                                    @if (! $usuario->activo)
                                        <form action="{{ route('admin.usuarios.reactivar', $usuario) }}" method="POST" data-confirm="{{ __('¿Reactivar a este usuario? Podrá volver a iniciar sesión.') }}" data-confirm-title="{{ __('Reactivar usuario') }}" data-confirm-type="primary" data-confirm-ok="{{ __('Sí, reactivar') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="{{ __('Reactivar') }}">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning" title="{{ __('Editar') }}" data-confirm="{{ __('¿Deseas editar este registro?') }}" data-confirm-title="{{ __('Confirmar edición') }}" data-confirm-type="warning" data-confirm-ok="{{ __('Sí, editar') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Papelera en 2 pasos: activo y dado de alta -> solo se pone inactivo;
                                         inactivo (o que nunca aceptó) -> pregunta si se elimina permanentemente. --}}
                                    @if ($usuario->activo && $aceptado)
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" data-confirm="{{ __('¿Desactivar a este usuario? Ya no podrá iniciar sesión, pero podrás reactivarlo cuando quieras.') }}" data-confirm-title="{{ __('Desactivar usuario') }}" data-confirm-type="warning" data-confirm-ok="{{ __('Sí, desactivar') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Desactivar') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" data-confirm="{{ __('¿Eliminar a este usuario de forma permanente? Esta acción no se puede deshacer.') }}" data-confirm-title="{{ __('Eliminar permanentemente') }}" data-confirm-type="danger" data-confirm-ok="{{ __('Sí, eliminar permanentemente') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Eliminar permanentemente') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="js-search-empty" style="display:none;">
                            <td colspan="99" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i> {{ __('No existe ningún usuario con esa búsqueda.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center py-3">{{ __('No hay usuarios registrados.') }}</td>
                        </tr>
                    @endforelse
                    @if ($usuarios->count())
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
            {{ $usuarios->links() }}
        </div>
    </div>

    {{-- Ventana "Fechas": una sola, se llena con los datos del usuario que se presionó. --}}
    <div class="modal fade" id="modalFechas" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
            <div class="modal-content" style="border: 1px solid var(--ui-border); border-radius: 14px; overflow: hidden; box-shadow: 0 12px 40px rgba(29,45,44,.25);">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title font-weight-bold" style="color: var(--ui-title);">{{ __('Fechas') }}</h5>
                        <div id="modalFechasNombre" class="text-muted" style="font-size:.9rem;"></div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Cerrar') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <ul id="modalFechasLista" class="list-unstyled mb-0"></ul>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
@include('partials.tabla-buscador')
<script>
(function () {
    if (!window.jQuery) return;
    jQuery('#modalFechas').on('show.bs.modal', function (e) {
        var btn = e.relatedTarget;
        if (!btn) return;
        var datos = [];
        try { datos = JSON.parse(btn.getAttribute('data-fechas') || '[]'); } catch (err) {}
        document.getElementById('modalFechasNombre').textContent = btn.getAttribute('data-nombre') || '';
        var lista = document.getElementById('modalFechasLista');
        lista.innerHTML = '';
        datos.forEach(function (d) {
            var li = document.createElement('li');
            li.className = 'd-flex align-items-center py-2';
            var icono = document.createElement('span');
            icono.className = 'fecha-icono bg-' + d.color;
            icono.innerHTML = '<i class="fas ' + d.icono + '"></i>';
            var texto = document.createElement('div');
            texto.className = 'ml-3';
            var etiqueta = document.createElement('div');
            etiqueta.className = 'text-muted';
            etiqueta.style.fontSize = '.8rem';
            etiqueta.textContent = d.etiqueta;
            var fecha = document.createElement('div');
            fecha.className = 'font-weight-bold';
            fecha.style.color = 'var(--ui-title)';
            fecha.textContent = d.fecha;
            texto.appendChild(etiqueta);
            texto.appendChild(fecha);
            li.appendChild(icono);
            li.appendChild(texto);
            lista.appendChild(li);
        });
    });
})();
</script>
@stop
