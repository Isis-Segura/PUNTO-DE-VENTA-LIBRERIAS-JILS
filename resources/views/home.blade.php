@extends('adminlte::page')

@section('title', __('Dashboard'))

@section('content_header')
    <h1>{{ __('Dashboard') }}</h1>
    @if ($sucursal ?? null)
        <p class="text-muted mb-0">{{ __('Información de tu sucursal') }}: <strong>{{ $sucursal->nombre_traducido }}</strong></p>
    @endif
@stop

@section('css')
    <style>
        .stat-box {
            border-radius: .75rem;
            padding: 1.1rem 1.25rem;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
            height: 100%;
        }
        .stat-box .stat-icon { font-size: 1.7rem; opacity: .85; }
        .stat-box h3 { margin: .15rem 0 0; font-weight: 700; }
        .stat-box small { opacity: .9; }
        .stat-ventashoy { background: linear-gradient(135deg, #21b573, #3ed58c); }
        .stat-ventasmes { background: linear-gradient(135deg, #e0762a, #f0975a); }
        .stat-productos { background: linear-gradient(135deg, #0d9488, #14b8a6); }
        .stat-alertas { background: linear-gradient(135deg, #dc3545, #f0656f); }

        .panel-card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }
        .panel-card .card-header {
            background: transparent;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        .rank-badge {
            width: 26px; height: 26px; border-radius: 50%;
            background: #6f5cf0; color: #fff; font-size: .75rem;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700;
        }
    </style>
@stop

@section('content')

    @if (! ($sucursal ?? null))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ __('Todavía no tienes una sucursal asignada. Contacta al Administrador General para que te asigne una.') }}
        </div>
    @else

        {{-- Contadores --}}
        <div class="row mb-3">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-box stat-ventashoy d-flex justify-content-between align-items-center">
                    <div>
                        <small>{{ __('Ventas de hoy') }}</small>
                        <h3>${{ number_format($ventasHoyTotal, 2) }}</h3>
                        <small>{{ $ventasHoyCount }} {{ __('tickets') }}</small>
                    </div>
                    <i class="fas fa-cash-register stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-box stat-ventasmes d-flex justify-content-between align-items-center">
                    <div>
                        <small>{{ __('Ventas del mes') }}</small>
                        <h3>${{ number_format($ventasMesTotal, 2) }}</h3>
                        <small>{{ $ventasMesCount }} {{ __('tickets') }}</small>
                    </div>
                    <i class="fas fa-chart-line stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-box stat-productos d-flex justify-content-between align-items-center">
                    <div>
                        <small>{{ __('Productos') }}</small>
                        <h3>{{ $totalProductos }}</h3>
                    </div>
                    <i class="fas fa-box stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-box stat-alertas d-flex justify-content-between align-items-center">
                    <div>
                        <small>{{ __('Bajo stock') }}</small>
                        <h3>{{ $bajoStock->count() }}</h3>
                    </div>
                    <i class="fas fa-exclamation-triangle stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="mb-4">
            @can('es-admin-o-gerente')
                <a href="{{ route('sucursales.show', $sucursal) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-boxes"></i> {{ __('Ver inventario de mi sucursal') }}
                </a>
            @endcan
            @can('puede-vender')
                <a href="{{ route('ventas.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-cash-register"></i> {{ __('Ir al punto de venta') }}
                </a>
            @endcan
        </div>

        <div class="row">
            {{-- Top productos vendidos --}}
            <div class="col-lg-6 mb-4">
                <div class="card panel-card h-100">
                    <div class="card-header">
                        <i class="fas fa-trophy mr-1 text-warning"></i> {{ __('Más vendidos este mes') }}
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <tbody>
                                @forelse ($topProductos as $i => $dv)
                                    <tr>
                                        <td class="align-middle" style="width: 40px;">
                                            <span class="rank-badge">{{ $i + 1 }}</span>
                                        </td>
                                        <td class="align-middle">{{ $dv->producto->nombre ?? '-' }}</td>
                                        <td class="align-middle text-right font-weight-bold">
                                            {{ $dv->total_vendido }} {{ __('vendidos') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-3 text-muted">{{ __('Aún no hay ventas este mes.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Alertas de bajo stock --}}
            <div class="col-lg-6 mb-4">
                <div class="card panel-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-exclamation-triangle mr-1 text-danger"></i> {{ __('Alertas de bajo stock') }}</span>
                        @can('es-admin-o-gerente')
                            <a href="{{ route('sucursales.show', $sucursal) }}" class="small">{{ __('Ver inventario') }}</a>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <tbody>
                                @forelse ($bajoStock as $inv)
                                    <tr>
                                        <td class="align-middle">{{ $inv->producto->nombre ?? '-' }}</td>
                                        <td class="align-middle text-right">
                                            <span class="badge badge-danger">{{ $inv->cantidad }} / {{ $inv->stock_minimo }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-3 text-muted">{{ __('Todo tu inventario está en buen nivel.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ventas recientes --}}
        <div class="card panel-card mb-4">
            <div class="card-header">
                <i class="fas fa-receipt mr-1"></i> {{ __('Ventas recientes de tu sucursal') }}
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Folio') }}</th>
                            <th>{{ __('Cajero') }}</th>
                            <th>{{ __('Fecha') }}</th>
                            <th class="text-right">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ventasRecientes as $venta)
                            <tr>
                                <td>{{ $venta->folio }}</td>
                                <td>{{ $venta->cajero->name ?? '-' }}</td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right font-weight-bold">${{ number_format($venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">{{ __('Aún no hay ventas registradas.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop
