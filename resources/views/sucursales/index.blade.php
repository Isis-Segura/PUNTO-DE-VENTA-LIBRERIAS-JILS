@extends('adminlte::page')

@section('title', __('Sucursales'))

@section('content_header')
    <h1>{{ __('Sucursales') }}</h1>

    <div class="js-search-empty text-center text-muted py-4" style="display:none;">
        <i class="fas fa-search fa-2x mb-2 d-block"></i>
        {{ __('No existe ninguna sucursal con esa búsqueda.') }}
    </div>

@stop

@section('css')
    <style>
        .sucursal-card {
            border: none;
            border-radius: .75rem;
            border-top: 4px solid var(--suc-accent, #6f5cf0);
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
            transition: transform .15s ease, box-shadow .15s ease;
            height: 100%;
        }
        .sucursal-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,.10);
        }
        .sucursal-card.is-inactiva { border-top-color: #adb5bd; opacity: .85; }

        .sucursal-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--suc-accent, #6f5cf0), var(--suc-accent-2, #8f7bff));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .sucursal-card.is-inactiva .sucursal-avatar {
            background: linear-gradient(135deg, #adb5bd, #ced4da);
        }

        .sucursal-meta {
            font-size: .85rem;
            color: #6c757d;
        }
        .sucursal-meta i {
            width: 18px;
            color: var(--suc-accent, #6f5cf0);
        }

        .summary-box {
            border-radius: .75rem;
            padding: 1.1rem 1.25rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }
        .summary-box i { font-size: 1.8rem; opacity: .85; }
        .summary-box h3 { margin: 0; font-weight: 700; }
        .summary-box small { opacity: .9; }
        .summary-total { background: linear-gradient(135deg, #6f5cf0, #8f7bff); }
        .summary-activas { background: linear-gradient(135deg, #21b573, #3ed58c); }
        .summary-inactivas { background: linear-gradient(135deg, #a0a6ad, #c2c7cc); }

        .badge-bajo-stock {
            background-color: #fff3f3;
            color: #dc3545;
            border: 1px solid #f5c2c7;
        }
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

    {{-- Resumen rápido --}}
    <div class="row mb-3">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="summary-box summary-total">
                <div>
                    <small>{{ __('Total de sucursales') }}</small>
                    <h3>{{ $totalSucursales }}</h3>
                </div>
                <i class="fas fa-store"></i>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="summary-box summary-activas">
                <div>
                    <small>{{ __('Activas') }}</small>
                    <h3>{{ $activas }}</h3>
                </div>
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box summary-inactivas">
                <div>
                    <small>{{ __('Inactivas') }}</small>
                    <h3>{{ $inactivas }}</h3>
                </div>
                <i class="fas fa-pause-circle"></i>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-muted">{{ __('Listado de sucursales') }}</h5>
        @can('es-admin')
            <a href="{{ route('sucursales.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('Nueva sucursal') }}
            </a>
        @endcan
    </div>

    <div class="row">
        @forelse ($sucursales as $sucursal)
            @php
                $colores = ['#6f5cf0', '#0d9488', '#e0762a', '#2563eb', '#c0247a', '#059669'];
                $accent = $colores[$sucursal->id % count($colores)];
            @endphp
            <div class="col-lg-4 col-md-6 mb-4 js-search-item"
                 data-search="{{ strtolower(($sucursal->nombre??'').' '.($sucursal->direccion??'').' '.($sucursal->telefono??'').' '.($sucursal->gerente->name??'')) }}">
                <div class="card sucursal-card {{ $sucursal->activa ? '' : 'is-inactiva' }}"
                     style="--suc-accent: {{ $accent }}; --suc-accent-2: {{ $accent }}cc;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="sucursal-avatar mr-3">
                                <i class="fas fa-store"></i>
                            </div>
                            <div>
                                <a href="{{ route('sucursales.show', $sucursal) }}" class="text-dark">
                                    <h5 class="mb-0 font-weight-bold">{{ $sucursal->nombre }}</h5>
                                </a>
                                @if ($sucursal->activa)
                                    <span class="badge badge-success">{{ __('Activa') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Inactiva') }}</span>
                                @endif
                                @if ($sucursal->bajo_stock_count > 0)
                                    <span class="badge badge-bajo-stock">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $sucursal->bajo_stock_count }} {{ __('bajo stock') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="sucursal-meta mb-1">
                            <i class="fas fa-map-marker-alt"></i> {{ $sucursal->direccion ?: __('Sin dirección registrada') }}
                        </div>
                        <div class="sucursal-meta mb-1">
                            <i class="fas fa-phone"></i> {{ $sucursal->telefono ?: '-' }}
                        </div>
                        <div class="sucursal-meta mb-1">
                            <i class="fas fa-user-tie"></i> {{ $sucursal->gerente->name ?? __('Sin gerente asignado') }}
                        </div>
                        <div class="sucursal-meta">
                            <i class="fas fa-box"></i> {{ $sucursal->productos_count }} {{ __('productos') }}
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <a href="{{ route('sucursales.show', $sucursal) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-boxes"></i> {{ __('Ver inventario') }}
                        </a>

                        @can('es-admin')
                            <div>
                                <a href="{{ route('sucursales.edit', $sucursal) }}" class="btn btn-sm btn-warning" data-confirm="{{ __('¿Deseas editar este registro?') }}" data-confirm-title="{{ __('Confirmar edición') }}" data-confirm-type="warning" data-confirm-ok="{{ __('Sí, editar') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('sucursales.destroy', $sucursal) }}" method="POST" class="d-inline" data-confirm="{{ __('¿Eliminar esta sucursal?') }}" data-confirm-title="{{ __('¿Eliminar?') }}" data-confirm-type="danger" data-confirm-ok="{{ __('Sí, eliminar') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fas fa-store fa-2x mb-2"></i>
                        <p class="mb-0">{{ __('No hay sucursales registradas.') }}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{ $sucursales->links() }}
@stop


@section('js')
<script>
(function(){
  function filtrar(texto){
    var q=(texto||'').toString().trim().toLowerCase();
    var items=document.querySelectorAll('.js-search-item');
    var n=0;
    items.forEach(function(el){
      var ok=!q||(el.getAttribute('data-search')||'').indexOf(q)!==-1;
      el.style.display=ok?'':'none';
      if(ok)n++;
    });
    var empty=document.querySelector('.js-search-empty');
    if(empty) empty.style.display=(items.length&&n===0)?'':'none';
  }
  document.addEventListener('input',function(e){
    if(e.target&&(e.target.name==='q'||e.target.name==='adminlteSearch')) filtrar(e.target.value);
  });
  document.addEventListener('submit',function(e){
    var f=e.target, inp=f&&f.querySelector&&f.querySelector('input[name="adminlteSearch"],input[name="q"]');
    if(inp&&document.querySelector('.js-search-item')){ e.preventDefault(); filtrar(inp.value); }
  });
  var p=new URLSearchParams(location.search);
  var ini=p.get('q')||p.get('adminlteSearch')||'';
  if(ini) filtrar(ini);
})();
</script>
@stop
