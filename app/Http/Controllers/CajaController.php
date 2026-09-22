<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Sucursal;
use Illuminate\Http\Request;

/**
 * Gestión de cajas por sucursal.
 * Solo Administrador General y Gerentes (limitados a su sucursal).
 * Cumple el requerimiento del protocolo: "Registrar nuevas cajas" y "Eliminar cajas".
 */
class CajaController extends Controller
{
    /**
     * Listado de cajas (filtrado por sucursal según el rol).
     */
    public function index(Request $request)
    {
        $query = Caja::with('sucursal')->orderBy('nombre');

        // Filtrar por sucursales permitidas
        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null) {
            $query->whereIn('sucursal_id', $ids);
        }

        // Búsqueda opcional
        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        // Filtro por sucursal (útil para el Admin)
        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        $cajas = $query->paginate(15)->withQueryString();
        $sucursales = $this->sucursalesVisibles();

        return view('cajas.index', compact('cajas', 'sucursales'));
    }

    /**
     * Formulario para registrar una nueva caja.
     */
    public function create()
    {
        $sucursales = $this->sucursalesVisibles();

        return view('cajas.create', compact('sucursales'));
    }

    /**
     * Guarda la nueva caja.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id'  => ['required', 'exists:sucursales,id'],
            'nombre'       => ['required', 'string', 'max:80'],
            'descripcion'  => ['nullable', 'string', 'max:255'],
            'activa'       => ['required', 'boolean'],
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        // Validar nombre único dentro de la sucursal
        $existe = Caja::where('sucursal_id', $data['sucursal_id'])
            ->where('nombre', $data['nombre'])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['nombre' => 'Ya existe una caja con ese nombre en la sucursal seleccionada.']);
        }

        Caja::create($data);

        return redirect()
            ->route('cajas.index')
            ->with('success', 'Caja creada correctamente.');
    }

    /**
     * Formulario de edición.
     */
    public function edit(Caja $caja)
    {
        $this->verificarAccesoSucursal($caja->sucursal_id);

        $sucursales = $this->sucursalesVisibles();

        return view('cajas.edit', compact('caja', 'sucursales'));
    }

    /**
     * Actualiza una caja existente.
     */
    public function update(Request $request, Caja $caja)
    {
        $this->verificarAccesoSucursal($caja->sucursal_id);

        $data = $request->validate([
            'sucursal_id'  => ['required', 'exists:sucursales,id'],
            'nombre'       => ['required', 'string', 'max:80'],
            'descripcion'  => ['nullable', 'string', 'max:255'],
            'activa'       => ['required', 'boolean'],
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        // Validar nombre único (excepto la propia caja)
        $existe = Caja::where('sucursal_id', $data['sucursal_id'])
            ->where('nombre', $data['nombre'])
            ->where('id', '!=', $caja->id)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['nombre' => 'Ya existe una caja con ese nombre en la sucursal seleccionada.']);
        }

        $caja->update($data);

        return redirect()
            ->route('cajas.index')
            ->with('success', 'Caja actualizada correctamente.');
    }

    /**
     * Elimina una caja.
     */
    public function destroy(Caja $caja)
    {
        $this->verificarAccesoSucursal($caja->sucursal_id);

        $caja->delete();

        return redirect()
            ->route('cajas.index')
            ->with('success', 'Caja eliminada correctamente.');
    }

    // ------------------------------------------------------------------
    // Helpers de acceso (igual patrón que ProductoController)
    // ------------------------------------------------------------------

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
