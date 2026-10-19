<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Punto de entrada del logo / "dashboard_url".
     * - Administrador → panel global /admin
     * - Gerente / Cajero → dashboard de su sucursal
     */
    public function index()
    {
        $user = auth()->user();

        // El logo de AdminLTE siempre va a /home; el admin debe ver su panel.
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }

        $sucursal = $user->sucursal;

        // Gerente/Cajero sin sucursal asignada.
        if (! $sucursal) {
            return view('home', ['sucursal' => null]);
        }

        $hoy = now();

        $ventasHoyQuery = Venta::where('sucursal_id', $sucursal->id)
            ->whereDate('created_at', $hoy->toDateString());
        $ventasHoyCount = (clone $ventasHoyQuery)->count();
        $ventasHoyTotal = (clone $ventasHoyQuery)->sum('total');

        $ventasMesQuery = Venta::where('sucursal_id', $sucursal->id)
            ->whereMonth('created_at', $hoy->month)
            ->whereYear('created_at', $hoy->year);
        $ventasMesTotal = (clone $ventasMesQuery)->sum('total');
        $ventasMesCount = (clone $ventasMesQuery)->count();

        $totalProductos = Producto::where('sucursal_id', $sucursal->id)->count();

        $bajoStock = Inventario::with('producto')
            ->whereHas('producto', fn ($q) => $q->where('sucursal_id', $sucursal->id))
            ->whereColumn('cantidad', '<=', 'stock_minimo')
            ->orderBy('cantidad')
            ->take(5)
            ->get();

        $topProductos = DetalleVenta::select('producto_id')
            ->selectRaw('SUM(cantidad) as total_vendido')
            ->whereHas('venta', fn ($q) => $q->where('sucursal_id', $sucursal->id)
                ->whereMonth('created_at', $hoy->month)
                ->whereYear('created_at', $hoy->year))
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();

        $ventasRecientes = Venta::with('cajero')
            ->where('sucursal_id', $sucursal->id)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact(
            'sucursal',
            'ventasHoyCount',
            'ventasHoyTotal',
            'ventasMesTotal',
            'ventasMesCount',
            'totalProductos',
            'bajoStock',
            'topProductos',
            'ventasRecientes'
        ));
    }
}
