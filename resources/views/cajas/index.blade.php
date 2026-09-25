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
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <span>{{ __('Listado de cajas') }}</span>
            <a href="{{ route('cajas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nueva caja') }}
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
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
                        <tr class="js-search-empty" style="display:none;">
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i> {{ __('No existe ninguna caja con esa búsqueda.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('No hay cajas registradas.') }}
                            </td>
                        </tr>
                    @endforelse
                    @if ($cajas->count())
                        <tr class="js-search-empty" style="display:none;">
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-search mr-1"></i> {{ __('No existe ninguna caja con esa búsqueda.') }}
                            </td>
                        </tr>
                    @endif
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
