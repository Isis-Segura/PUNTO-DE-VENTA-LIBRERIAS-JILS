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
            abort(403, __('messages.branch_permission'));
        }

        // Antes no había tope máximo, así que un número extremadamente
        // grande (ej. la columna es unsignedInteger, tope real ~4,294,967,295)
        // rompía la base de datos con un error SQL crudo mostrado en
        // pantalla. Ponemos un máximo razonable y lo validamos aquí para
        // que el error se muestre de forma amigable en vez de que truene la
        // consulta SQL.
        $data = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0', 'max:999999999'],
            'stock_minimo' => ['required', 'integer', 'min:0', 'max:999999999'],
        ], [
            'cantidad.max' => 'La cantidad no puede ser mayor a 999,999,999.',
            'stock_minimo.max' => 'El stock mínimo no puede ser mayor a 999,999,999.',
        ]);

        $inventario->update($data);

        return back()->with('success', __('messages.inventory_updated'));
    }
}
