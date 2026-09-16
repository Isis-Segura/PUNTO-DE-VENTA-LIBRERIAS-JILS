<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Antes, este controlador estaba completamente vacío y no tenía ninguna
 * ruta registrada: la vista existía pero nadie podía llegar a ella y no se
 * podían crear géneros reales desde la interfaz. Aquí implementamos el
 * CRUD completo (disponible para Administrador y Gerente, según el
 * protocolo del proyecto).
 */
class GeneroController extends Controller
{
    /**
     * Listado de géneros.
     */
    public function index(Request $request)
    {
        $query = Genero::withCount('productos')->orderBy('nombre');

        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        $generos = $query->paginate(15)->withQueryString();

        return view('admin.generos.index', compact('generos'));
    }

    /**
     * Formulario para registrar una nueva género.
     */
    public function create()
    {
        return view('admin.generos.create');
    }

    /**
     * Guarda la nueva género.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150', 'unique:generos,nombre'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ]);

        Genero::create($data);

        return redirect()
            ->route('generos.index')
            ->with('success', 'Género creada correctamente.');
    }

    /**
     * Muestra el detalle de una género (no se usa por ahora; redirige al listado).
     */
    public function show(Genero $genero)
    {
        return redirect()->route('generos.index');
    }

    /**
     * Formulario para editar una género existente.
     */
    public function edit(Genero $genero)
    {
        return view('admin.generos.edit', compact('genero'));
    }

    /**
     * Actualiza los datos de una género.
     */
    public function update(Request $request, Genero $genero)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150', Rule::unique('generos', 'nombre')->ignore($genero->id)],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ]);

        $genero->update($data);

        return redirect()
            ->route('generos.index')
            ->with('success', 'Género actualizada correctamente.');
    }

    /**
     * Elimina una género, siempre que no tenga productos asociados
     * (para no dejar productos huérfanos ni romper referencias).
     */
    public function destroy(Genero $genero)
    {
        if ($genero->productos()->exists()) {
            return back()->with('error', 'No puedes eliminar una género que ya tiene productos asignados.');
        }

        $genero->delete();

        return redirect()
            ->route('generos.index')
            ->with('success', 'Género eliminada correctamente.');
    }
}
