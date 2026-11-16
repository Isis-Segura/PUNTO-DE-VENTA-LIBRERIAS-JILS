<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Genero;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['generos', 'categoria', 'sucursal', 'inventario']);

        $this->aplicarFiltroSucursal($query);

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where(fn ($b) => $b->where('nombre', 'like', "%{$q}%")
                    ->orWhere('nombre_en', 'like', "%{$q}%")
                    ->orWhere('codigo', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%")
                    ->orWhere('descripcion_en', 'like', "%{$q}%")
                    ->orWhere('autor', 'like', "%{$q}%")
                    ->orWhere('editorial', 'like', "%{$q}%"))
                    ->orWhereHas('generos', fn ($generos) => $generos->buscarBilingue($q))
                    ->orWhereHas('categoria', fn ($categoria) => $categoria->buscarBilingue($q))
                    ->orWhereHas('sucursal', fn ($sucursal) => $sucursal->where('nombre', 'like', "%{$q}%"));
            });
        }

        $productos = $query->orderBy('nombre')->paginate(12)->withQueryString();
        $sucursales = $this->sucursalesVisibles();

        return view('productos.index', compact('productos', 'sucursales'));
    }

    public function create()
    {
        $sucursales = $this->sucursalesVisibles();
        $categorias = Categoria::orderBy('nombre')->get();
        $generos = Genero::orderBy('nombre')->get();

        return view('productos.create', compact('sucursales', 'categorias', 'generos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'genero_ids' => ['required', 'array', 'min:1', 'max:5'],
            'genero_ids.*' => ['integer', 'exists:generos,id'],
            'nombre' => ['required', 'string', 'max:120'],
            'nombre_en' => ['nullable', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:50'],
            'descripcion_en' => ['nullable', 'string', 'max:50'],
            'autor' => ['nullable', 'string', 'max:120'],
            'editorial' => ['nullable', 'string', 'max:120'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'cantidad_inicial' => ['required', 'integer', 'min:0', 'max:100000'],
            'stock_minimo' => ['required', 'integer', 'min:0', 'max:100000'],
            'activo' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ], [
            'genero_ids.required' => 'Selecciona al menos 1 género.',
            'genero_ids.min' => 'Selecciona al menos 1 género.',
            'genero_ids.max' => 'Solo puedes seleccionar hasta 5 géneros.',
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        DB::transaction(function () use ($request, $data) {
            $sucursal = Sucursal::query()
                ->whereKey($data['sucursal_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $codigo = $this->generarCodigo($sucursal);
            $rutaImagen = $request->hasFile('imagen')
                ? $this->guardarPortada($request->file('imagen'), $data['nombre'])
                : null;

            $producto = Producto::create([
                'sucursal_id' => $data['sucursal_id'],
                'categoria_id' => $data['categoria_id'] ?? null,
                'nombre' => $data['nombre'],
                'nombre_en' => $data['nombre_en'] ?? null,
                'descripcion' => $data['descripcion'] ?? null,
                'descripcion_en' => $data['descripcion_en'] ?? null,
                'codigo' => $codigo,
                'autor' => $data['autor'] ?? null,
                'editorial' => $data['editorial'] ?? null,
                'precio' => $data['precio'],
                'imagen' => $rutaImagen,
                'activo' => $data['activo'],
            ]);

            $producto->generos()->sync($data['genero_ids']);

            Inventario::create([
                'producto_id' => $producto->id,
                'cantidad' => $data['cantidad_inicial'],
                'stock_minimo' => $data['stock_minimo'],
            ]);
        });

        return redirect()->route('productos.index')->with('success', __('messages.product_created'));
    }

    public function edit(Producto $producto)
    {
        $this->verificarAccesoSucursal($producto->sucursal_id);

        $sucursales = $this->sucursalesVisibles();
        $categorias = Categoria::orderBy('nombre')->get();
        $generos = Genero::orderBy('nombre')->get();
        $producto->load(['inventario', 'generos']);

        return view('productos.edit', compact('producto', 'sucursales', 'categorias', 'generos'));
    }

    public function update(Request $request, Producto $producto)
    {
        $this->verificarAccesoSucursal($producto->sucursal_id);

        $rules = [
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'genero_ids' => ['required', 'array', 'min:1', 'max:5'],
            'genero_ids.*' => ['integer', 'exists:generos,id'],
            'nombre' => ['required', 'string', 'max:120'],
            'nombre_en' => ['nullable', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:50'],
            'descripcion_en' => ['nullable', 'string', 'max:50'],
            'autor' => ['nullable', 'string', 'max:120'],
            'editorial' => ['nullable', 'string', 'max:120'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'stock_minimo' => ['required', 'integer', 'min:0', 'max:100000'],
            'activo' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'quitar_imagen' => ['nullable', 'boolean'],
        ];

        // Admin y Gerente pueden ajustar existencia
        if (auth()->user()->isAdmin() || auth()->user()->isGerente()) {
            $rules['cantidad'] = ['required', 'integer', 'min:0', 'max:999999999'];
        }

        $data = $request->validate($rules, [
            'genero_ids.required' => 'Selecciona al menos 1 género.',
            'genero_ids.max' => 'Solo puedes seleccionar hasta 5 géneros.',
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        $imagenAnterior = $producto->imagen;
        $rutaImagen = $request->boolean('quitar_imagen') ? null : $imagenAnterior;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $this->guardarPortada($request->file('imagen'), $data['nombre']);
        }

        $producto->update([
            'sucursal_id' => $data['sucursal_id'],
            'categoria_id' => $data['categoria_id'] ?? null,
            'nombre' => $data['nombre'],
            'nombre_en' => $data['nombre_en'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'descripcion_en' => $data['descripcion_en'] ?? null,
            'autor' => $data['autor'] ?? null,
            'editorial' => $data['editorial'] ?? null,
            'precio' => $data['precio'],
            'imagen' => $rutaImagen,
            'activo' => $data['activo'],
        ]);

        if ($imagenAnterior && $imagenAnterior !== $rutaImagen) {
            Storage::disk('covers')->delete($imagenAnterior);
        }

        $producto->generos()->sync($data['genero_ids']);

        if ($producto->inventario) {
            $inv = ['stock_minimo' => $data['stock_minimo']];
            if (array_key_exists('cantidad', $data)) {
                $inv['cantidad'] = $data['cantidad'];
            }
            $producto->inventario->update($inv);
        }

        return redirect()->route('productos.index')->with('success', __('messages.product_updated'));
    }

    public function destroy(Producto $producto)
    {
        $this->verificarAccesoSucursal($producto->sucursal_id);

        if ($producto->detalleVentas()->exists()) {
            return back()->with('error', __('messages.product_has_sales'));
        }

        if ($producto->imagen) {
            Storage::disk('covers')->delete($producto->imagen);
        }

        $producto->inventario()?->delete();
        $producto->delete();

        return redirect()->route('productos.index')->with('success', __('messages.product_deleted'));
    }

    private function guardarPortada($imagen, string $nombreProducto): string
    {
        $directorio = public_path('portadas');
        File::ensureDirectoryExists($directorio);

        $base = Str::slug($nombreProducto) ?: 'portada';
        $extension = strtolower($imagen->getClientOriginalExtension() ?: $imagen->guessExtension() ?: 'jpg');
        $nombre = $base.'-'.Str::lower(Str::random(8)).'.'.$extension;

        $imagen->move($directorio, $nombre);

        if (! is_file($directorio.DIRECTORY_SEPARATOR.$nombre)) {
            throw ValidationException::withMessages([
                'imagen' => __('No se pudo guardar la imagen. Revisa los permisos de la carpeta public/portadas.'),
            ]);
        }

        return $nombre;
    }

    private function generarCodigo(Sucursal $sucursal): string
    {
        if ($sucursal->id > 99) {
            throw ValidationException::withMessages([
                'sucursal_id' => 'No se pueden generar SKU de 4 dígitos para sucursales con ID mayor a 99.',
            ]);
        }

        $numero = (int) $sucursal->sku_sequence;

        while ($numero < 99) {
            $numero++;
            $codigo = sprintf('%02d%02d', $sucursal->id, $numero);

            if (! $sucursal->productos()->where('codigo', $codigo)->exists()) {
                $sucursal->sku_sequence = $numero;
                $sucursal->save();

                return $codigo;
            }
        }

        throw ValidationException::withMessages([
            'sucursal_id' => 'Esta sucursal ya alcanzó el límite de 99 SKU de cuatro dígitos.',
        ]);
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
            abort(403, __('messages.branch_permission'));
        }
    }
}
