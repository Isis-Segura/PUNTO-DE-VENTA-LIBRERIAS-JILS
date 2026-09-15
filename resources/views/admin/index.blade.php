@extends('adminlte::page')

@section('title', __('Dashboard'))

@section('content_header')
    <h1>{{ __('Dashboard general') }}</h1>
    <p class="text-muted mb-0">{{ __('Resumen de todas las sucursales.') }}</p>
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
        .stat-sucursales { background: linear-gradient(135deg, #6f5cf0, #8f7bff); }
        .stat-productos { background: linear-gradient(135deg, #0d9488, #14b8a6); }
        .stat-usuarios { background: linear-gradient(135deg, #2563eb, #3b82f6); }
        .stat-alertas { background: linear-gradient(135deg, #dc3545, #f0656f); }
        .stat-ventashoy { background: linear-gradient(135deg, #21b573, #3ed58c); }
        .stat-ventasmes { background: linear-gradient(135deg, #e0762a, #f0975a); }

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

    {{-- Fila 1: contadores generales --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-box stat-sucursales d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ __('Sucursales') }}</small>
                    <h3>{{ $sucursalesActivas }}/{{ $totalSucursales }}</h3>
                    <small>{{ __('activas de un total de') }} {{ $totalSucursales }}</small>
                </div>
                <i class="fas fa-store stat-icon"></i>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-box stat-productos d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ __('Productos registrados') }}</small>
                    <h3>{{ $totalProductos }}</h3>
                </div>
                <i class="fas fa-box stat-icon"></i>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-box stat-usuarios d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ __('Usuarios') }}</small>
                    <h3>{{ $totalUsuarios }}</h3>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-box stat-alertas d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ __('Productos bajo stock') }}</small>
                    <h3>{{ $bajoStockCount }}</h3>
                </div>
                <i class="fas fa-exclamation-triangle stat-icon"></i>
            </div>
        </div>
    </div>

    {{-- Fila 2: ventas --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="stat-box stat-ventashoy d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ __('Ventas de hoy') }}</small>
                    <h3>${{ number_format($ventasHoyTotal, 2) }}</h3>
                    <small>{{ $ventasHoyCount }} {{ __('tickets') }}</small>
                </div>
                <i class="fas fa-cash-register stat-icon"></i>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-box stat-ventasmes d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ __('Ventas del mes') }}</small>
                    <h3>${{ number_format($ventasMesTotal, 2) }}</h3>
                    <small>{{ $ventasMesCount }} {{ __('tickets') }}</small>
                </div>
                <i class="fas fa-chart-line stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Ranking de sucursales --}}
        <div class="col-lg-6 mb-4">
            <div class="card panel-card h-100">
                <div class="card-header">
                    <i class="fas fa-trophy mr-1 text-warning"></i> {{ __('Sucursales por ventas este mes') }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @forelse ($sucursalesTop as $i => $s)
                                <tr>
                                    <td class="align-middle" style="width: 40px;">
                                        <span class="rank-badge">{{ $i + 1 }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('sucursales.show', $s) }}">{{ $s->nombre }}</a>
                                        <br><small class="text-muted">{{ $s->productos_count }} {{ __('productos') }}</small>
                                    </td>
                                    <td class="align-middle text-right font-weight-bold">
                                        ${{ number_format($s->ventas_mes_total ?? 0, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-3 text-muted">{{ __('Aún no hay ventas registradas.') }}</td>
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
                    <a href="{{ route('sucursales.index') }}" class="small">{{ __('Ver sucursales') }}</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @forelse ($bajoStockList as $inv)
                                <tr>
                                    <td class="align-middle">
                                        {{ $inv->producto->nombre ?? '-' }}
                                        <br><small class="text-muted">{{ $inv->producto->sucursal->nombre ?? '-' }}</small>
                                    </td>
                                    <td class="align-middle text-right">
                                        <span class="badge badge-danger">{{ $inv->cantidad }} / {{ $inv->stock_minimo }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-3 text-muted">{{ __('Ninguna sucursal tiene productos bajo stock.') }}</td>
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
            <i class="fas fa-receipt mr-1"></i> {{ __('Ventas recientes') }}
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Folio') }}</th>
                        <th>{{ __('Sucursal') }}</th>
                        <th>{{ __('Cajero') }}</th>
                        <th>{{ __('Fecha') }}</th>
                        <th class="text-right">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventasRecientes as $venta)
                        <tr>
                            <td>{{ $venta->folio }}</td>
                            <td>{{ $venta->sucursal->nombre ?? '-' }}</td>
                            <td>{{ $venta->cajero->name ?? '-' }}</td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-right font-weight-bold">${{ number_format($venta->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">{{ __('Aún no hay ventas registradas.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
