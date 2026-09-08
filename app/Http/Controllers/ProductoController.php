<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'sucursal', 'inventario']);

        $this->aplicarFiltroSucursal($query);

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        $productos = $query->orderBy('nombre')->paginate(10)->withQueryString();
        $sucursales = $this->sucursalesVisibles();

        return view('productos.index', compact('productos', 'sucursales'));
    }

    public function create()
    {
        $sucursales = $this->sucursalesVisibles();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.create', compact('sucursales', 'categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
            'codigo' => ['nullable', 'string', 'max:60'],
            'precio' => ['required', 'numeric', 'min:0'],
            'cantidad_inicial' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'activo' => ['required', 'boolean'],
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        $producto = Producto::create([
            'sucursal_id' => $data['sucursal_id'],
            'categoria_id' => $data['categoria_id'] ?? null,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'precio' => $data['precio'],
            'activo' => $data['activo'],
        ]);

        Inventario::create([
            'producto_id' => $producto->id,
            'cantidad' => $data['cantidad_inicial'],
            'stock_minimo' => $data['stock_minimo'],
        ]);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $this->verificarAccesoSucursal($producto->sucursal_id);

        $sucursales = $this->sucursalesVisibles();
        $categorias = Categoria::orderBy('nombre')->get();
        $producto->load('inventario');

        return view('productos.edit', compact('producto', 'sucursales', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $this->verificarAccesoSucursal($producto->sucursal_id);

        $data = $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string'],
            'codigo' => ['nullable', 'string', 'max:60'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'activo' => ['required', 'boolean'],
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        $producto->update([
            'sucursal_id' => $data['sucursal_id'],
            'categoria_id' => $data['categoria_id'] ?? null,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'precio' => $data['precio'],
            'activo' => $data['activo'],
        ]);

        // El stock mínimo se puede editar aquí; la cantidad real se ajusta desde Inventario
        if ($producto->inventario) {
            $producto->inventario->update(['stock_minimo' => $data['stock_minimo']]);
        }

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->verificarAccesoSucursal($producto->sucursal_id);

        if ($producto->detalleVentas()->exists()) {
            return back()->with('error', 'No puedes eliminar un producto que ya tiene ventas registradas. Desactívalo en su lugar.');
        }

        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    /**
     * Limita el listado a la(s) sucursal(es) del usuario, salvo que sea Admin.
     */
    private function aplicarFiltroSucursal($query): void
    {
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null) {
            $query->whereIn('sucursal_id', $ids);
        }
    }

    /**
     * Sucursales que el usuario puede elegir en los formularios.
     */
    private function sucursalesVisibles()
    {
        $query = Sucursal::orderBy('nombre');

        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null) {
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }

    /**
     * Corta el acceso si un Gerente intenta crear/editar un producto de
     * una sucursal que no es la suya (ej. manipulando el formulario a mano).
     */
    private function verificarAccesoSucursal(int $sucursalId): void
    {
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null && ! in_array($sucursalId, $ids, true)) {
            abort(403, 'No tienes permiso sobre esa sucursal.');
        }
    }
}
