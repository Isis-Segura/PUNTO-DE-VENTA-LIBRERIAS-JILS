@extends('adminlte::page')

@section('title', __('Usuarios'))

@section('content_header')
    <h1>{{ __('Usuarios') }}</h1>
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
            <span>{{ __('Listado de usuarios') }}</span>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nuevo usuario') }}
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Correo') }}</th>
                        <th>{{ __('Rol') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr class="js-search-item" data-search="{{ strtolower(($usuario->name??'').' '.($usuario->email??'').' '.($usuario->role->nombre??'').' '.($usuario->sucursal->nombre??'')) }}">
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->role->nombre ?? __('Sin rol') }}</td>
                            <td>{{ $usuario->sucursal->nombre ?? __('—') }}</td>
                            <td>
                                @if ($usuario->activo)
                                    <span class="badge badge-success">{{ __('Activo') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactivo') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning" data-confirm="{{ __('¿Deseas editar este registro?') }}" data-confirm-title="{{ __('Confirmar edición') }}" data-confirm-type="warning" data-confirm-ok="{{ __('Sí, editar') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline" data-confirm="{{ __('¿Eliminar este usuario?') }}" data-confirm-title="{{ __('¿Eliminar?') }}" data-confirm-type="danger" data-confirm-ok="{{ __('Sí, eliminar') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $usuarios->links() }}
        </div>
    </div>
@stop


@section('js')
<script>
(function(){
  function filtrar(texto){
    var q=(texto||'').toString().trim().toLowerCase();
    var items=document.querySelectorAll('tr.js-search-item');
    var n=0;
    items.forEach(function(el){
      var ok=!q||(el.getAttribute('data-search')||'').indexOf(q)!==-1;
      el.style.display=ok?'':'none';
      if(ok)n++;
    });
    var empty=document.querySelector('tr.js-search-empty');
    if(empty) empty.style.display=(items.length&&n===0)?'':'none';
  }
  document.addEventListener('input',function(e){
    if(e.target&&(e.target.name==='q'||e.target.name==='adminlteSearch')) filtrar(e.target.value);
  });
  document.addEventListener('submit',function(e){
    var f=e.target, inp=f&&f.querySelector&&f.querySelector('input[name="adminlteSearch"],input[name="q"]');
    if(inp&&document.querySelector('tr.js-search-item')){ e.preventDefault(); filtrar(inp.value); }
  });
  var p=new URLSearchParams(location.search);
  var ini=p.get('q')||p.get('adminlteSearch')||'';
  if(ini) filtrar(ini);
})();
</script>
@stop
