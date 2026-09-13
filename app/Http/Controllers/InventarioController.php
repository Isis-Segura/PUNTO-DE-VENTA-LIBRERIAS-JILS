<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventario::with(['producto.sucursal', 'producto.categoria']);

        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null) {
            $query->whereHas('producto', fn ($q) => $q->whereIn('sucursal_id', $ids));
        }

        if ($request->filled('sucursal_id')) {
            $query->whereHas('producto', fn ($q) => $q->where('sucursal_id', $request->sucursal_id));
        }

        if ($request->boolean('bajo_stock')) {
            $query->whereColumn('cantidad', '<=', 'stock_minimo');
        }

        $inventarios = $query->orderBy('cantidad')->paginate(15)->withQueryString();

        $sucursalesQuery = Sucursal::orderBy('nombre');
        if ($ids !== null) {
            $sucursalesQuery->whereIn('id', $ids);
        }
        $sucursales = $sucursalesQuery->get();

        return view('inventario.index', compact('inventarios', 'sucursales'));
    }

    /**
     * Ajusta manualmente la cantidad disponible de un producto
     * (ej. tras un conteo físico, una merma, o una nueva compra).
     */
    public function update(Request $request, Inventario $inventario)
    {
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null && ! in_array($inventario->producto->sucursal_id, $ids, true)) {
            abort(403, 'No tienes permiso sobre esa sucursal.');
        }

        $data = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
        ]);

        $inventario->update($data);

        return back()->with('success', 'Inventario actualizado correctamente.');
    }
}
