<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard del Administrador General: una vista global de todo el negocio
     * (todas las sucursales, todo el inventario, todas las ventas).
     */
    public function index()
    {
        $hoy = now();

        $totalSucursales = Sucursal::count();
        $sucursalesActivas = Sucursal::where('activa', true)->count();
        $totalProductos = Producto::count();
        $totalUsuarios = User::count();

        $ventasHoyQuery = Venta::whereDate('created_at', $hoy->toDateString());
        $ventasHoyCount = (clone $ventasHoyQuery)->count();
        $ventasHoyTotal = (clone $ventasHoyQuery)->sum('total');

        $ventasMesQuery = Venta::whereMonth('created_at', $hoy->month)->whereYear('created_at', $hoy->year);
        $ventasMesTotal = (clone $ventasMesQuery)->sum('total');
        $ventasMesCount = (clone $ventasMesQuery)->count();

        $bajoStockCount = Inventario::whereColumn('cantidad', '<=', 'stock_minimo')->count();

        $bajoStockList = Inventario::with(['producto.sucursal'])
            ->whereColumn('cantidad', '<=', 'stock_minimo')
            ->orderBy('cantidad')
            ->take(6)
            ->get();

        // Ranking de sucursales por ventas del mes actual.
        $sucursalesTop = Sucursal::withCount('productos')
            ->withSum(['ventas as ventas_mes_total' => function ($q) use ($hoy) {
                $q->whereMonth('created_at', $hoy->month)->whereYear('created_at', $hoy->year);
            }], 'total')
            ->orderByDesc('ventas_mes_total')
            ->take(5)
            ->get();

        $ventasRecientes = Venta::with(['sucursal', 'cajero'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.index', compact(
            'totalSucursales',
            'sucursalesActivas',
            'totalProductos',
            'totalUsuarios',
            'ventasHoyCount',
            'ventasHoyTotal',
            'ventasMesTotal',
            'ventasMesCount',
            'bajoStockCount',
            'bajoStockList',
            'sucursalesTop',
            'ventasRecientes'
        ));
    }
}
