<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Antes, este controlador estaba completamente vacío y no tenía ninguna
 * ruta registrada: la vista existía pero nadie podía llegar a ella y no se
 * podían crear categorías reales desde la interfaz. Aquí implementamos el
 * CRUD completo (disponible para Administrador y Gerente, según el
 * protocolo del proyecto).
 */
class CategoriaController extends Controller
{
    /**
     * Listado de categorías.
     */
    public function index(Request $request)
    {
        $query = Categoria::withCount('productos')->orderBy('nombre');

        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        $categorias = $query->paginate(15)->withQueryString();

        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Formulario para registrar una nueva categoría.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Guarda la nueva categoría.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:80', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);

        Categoria::create($data);

        return redirect()
            ->route('categorias.index')
            ->with('success', __('messages.category_created'));
    }

    /**
     * Muestra el detalle de una categoría (no se usa por ahora; redirige al listado).
     */
    public function show(Categoria $categoria)
    {
        return redirect()->route('categorias.index');
    }

    /**
     * Formulario para editar una categoría existente.
     */
    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Actualiza los datos de una categoría.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:80', Rule::unique('categorias', 'nombre')->ignore($categoria->id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);

        $categoria->update($data);

        return redirect()
            ->route('categorias.index')
            ->with('success', __('messages.category_updated'));
    }

    /**
     * Elimina una categoría, siempre que no tenga productos asociados
     * (para no dejar productos huérfanos ni romper referencias).
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return back()->with('error', __('messages.category_has_products'));
        }

        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('success', __('messages.category_deleted'));
    }
}
