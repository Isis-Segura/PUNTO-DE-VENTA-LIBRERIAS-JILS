@extends('adminlte::page')

@section('title', __('Ventas'))

@section('content_header')
    <h1>{{ __('Historial de ventas') }}</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('ventas.create') }}" class="btn btn-success">
            <i class="fas fa-cash-register"></i> {{ __('Nueva venta') }}
        </a>
    </div>

    <div class="card">
        @if ($sucursales->count() > 1)
            <div class="card-header">
                <form method="GET" class="form-inline">
                    <label class="mr-2 mb-0">{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">{{ __('Todas') }}</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ request('sucursal_id') == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Folio') }}</th>
                        <th>{{ __('Fecha') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Cajero') }}</th>
                        <th>{{ __('Método de pago') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th class="text-right">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $venta)
                        @php
                            $fecha = $venta->created_at;
                            $search = strtolower(implode(' ', array_filter([
                                $venta->folio,
                                $venta->sucursal->nombre ?? '',
                                $venta->cajero->name ?? '',
                                $venta->metodoPago->nombre ?? '',
                                // Fecha y hora (el buscador usa contains)
                                $fecha?->format('d/m/Y H:i'),
                                $fecha?->format('d/m/Y H'),
                                $fecha?->format('d/m/Y'),
                                $fecha?->format('d-m-Y H:i'),
                                $fecha?->format('d-m-Y'),
                                $fecha?->format('Y-m-d H:i'),
                                $fecha?->format('Y-m-d'),
                                $fecha?->format('d/m/y H:i'),
                                $fecha?->format('d/m'),
                                $fecha?->format('H:i'),
                                $fecha?->format('H'),
                                $fecha?->format('d'),
                                // sin ceros a la izquierda
                                $fecha ? ((int)$fecha->format('d')).'/'.((int)$fecha->format('m')).'/'.$fecha->format('Y').' '.$fecha->format('H:i') : '',
                                $fecha ? ((int)$fecha->format('d')).'/'.((int)$fecha->format('m')).'/'.$fecha->format('Y').' '.$fecha->format('H') : '',
                                $fecha ? ((int)$fecha->format('d')).'/'.((int)$fecha->format('m')).'/'.$fecha->format('Y') : '',
                            ])));
                        @endphp
                        <tr class="js-search-item" data-search="{{ $search }}">
                            <td>{{ $venta->folio }}</td>
                            <td>{{ $fecha?->format('d/m/Y H:i') }}</td>
                            <td>{{ $venta->sucursal->nombre ?? '—' }}</td>
                            <td>{{ $venta->cajero->name ?? '—' }}</td>
                            <td>{{ $venta->metodoPago->nombre ?? '—' }}</td>
                            <td>${{ number_format($venta->total, 2) }}</td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-info" title="{{ __('Ver ticket') }}">
                                    <i class="fas fa-receipt"></i> {{ __('Ver ticket') }}
                                </a>
                                @if (auth()->user()->isAdmin())
                                    <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm(@json(__('¿Eliminar el ticket :folio? Se devolverá el stock al inventario.', ['folio' => $venta->folio])));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Eliminar') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">{{ __('No hay ventas registradas.') }}</td>
                        </tr>
                    @endforelse
                    <tr class="js-search-empty" style="display:none;">
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-search mr-1"></i> {{ __('No existe ningún ticket con esa búsqueda.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($ventas->hasPages())
            <div class="card-footer">
                {{ $ventas->links() }}
            </div>
        @endif
    </div>
@stop

@section('js')
<script>
(function(){
  function filtrar(texto){
    var q=(texto||'').toString().trim().toLowerCase().replace(/\s+/g,' ');
    var items=document.querySelectorAll('tr.js-search-item');
    var n=0;
    items.forEach(function(el){
      var hay=(el.getAttribute('data-search')||'').replace(/\s+/g,' ');
      var ok=!q||hay.indexOf(q)!==-1;
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
