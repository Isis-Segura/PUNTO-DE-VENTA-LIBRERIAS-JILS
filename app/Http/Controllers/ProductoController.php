<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'sucursal', 'inventario']);

        $this->aplicarFiltroSucursal($query);

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        $productos = $query->orderBy('nombre')->paginate(12)->withQueryString();
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
            'precio' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'cantidad_inicial' => ['required', 'integer', 'min:0', 'max:999999999'],
            'stock_minimo' => ['required', 'integer', 'min:0', 'max:999999999'],
            'activo' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ], [
            'precio.max' => 'El precio no puede ser mayor a $99,999,999.99.',
            'cantidad_inicial.max' => 'La cantidad inicial no puede ser mayor a 999,999,999.',
            'stock_minimo.max' => 'El stock mínimo no puede ser mayor a 999,999,999.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.max' => 'La imagen no puede pesar más de 4 MB.',
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create([
            'sucursal_id' => $data['sucursal_id'],
            'categoria_id' => $data['categoria_id'] ?? null,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'precio' => $data['precio'],
            'imagen' => $rutaImagen,
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
            'precio' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock_minimo' => ['required', 'integer', 'min:0', 'max:999999999'],
            'activo' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'quitar_imagen' => ['nullable', 'boolean'],
        ], [
            'precio.max' => 'El precio no puede ser mayor a $99,999,999.99.',
            'stock_minimo.max' => 'El stock mínimo no puede ser mayor a 999,999,999.',
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        $rutaImagen = $producto->imagen;

        if ($request->boolean('quitar_imagen') && $rutaImagen) {
            Storage::disk('public')->delete($rutaImagen);
            $rutaImagen = null;
        }

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update([
            'sucursal_id' => $data['sucursal_id'],
            'categoria_id' => $data['categoria_id'] ?? null,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'codigo' => $data['codigo'] ?? null,
            'precio' => $data['precio'],
            'imagen' => $rutaImagen,
            'activo' => $data['activo'],
        ]);

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

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->inventario()?->delete();
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    private function aplicarFiltroSucursal($query): void
    {
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null) {
            $query->whereIn('sucursal_id', $ids);
        }
    }

    private function sucursalesVisibles()
    {
        $query = Sucursal::orderBy('nombre');
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null) {
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }

    private function verificarAccesoSucursal(int $sucursalId): void
    {
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null && ! in_array($sucursalId, $ids, true)) {
            abort(403, 'No tienes permiso sobre esa sucursal.');
        }
    }
}
