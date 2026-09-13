<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
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
