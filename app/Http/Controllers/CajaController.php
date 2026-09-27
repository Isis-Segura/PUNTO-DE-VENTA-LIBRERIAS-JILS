<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Sucursal;
use Illuminate\Http\Request;

/**
 * Gestión de cajas por sucursal.
 * Solo Administrador General y Gerentes (limitados a su sucursal).
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

        // Búsqueda: nombre, descripción o sucursal
        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%")
                    ->orWhereHas('sucursal', function ($s) use ($q) {
                        $s->where('nombre', 'like', "%{$q}%");
                    });
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

    public function create()
    {
        $sucursales = $this->sucursalesVisibles();

        return view('cajas.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id'  => ['required', 'exists:sucursales,id'],
            'nombre'       => ['required', 'string', 'max:80'],
            'descripcion'  => ['nullable', 'string', 'max:255'],
            'activa'       => ['required', 'boolean'],
        ]);

        $this->verificarAccesoSucursal((int) $data['sucursal_id']);

        $existe = Caja::where('sucursal_id', $data['sucursal_id'])
            ->where('nombre', $data['nombre'])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', __('Ya existe una caja con ese nombre en la sucursal seleccionada.'));
        }

        Caja::create($data);

        return redirect()
            ->route('cajas.index')
            ->with('success', __('messages.cash_register_created'));
    }

    public function edit(Caja $caja)
    {
        $this->verificarAccesoSucursal($caja->sucursal_id);
        $sucursales = $this->sucursalesVisibles();

        return view('cajas.edit', compact('caja', 'sucursales'));
    }

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

        $existe = Caja::where('sucursal_id', $data['sucursal_id'])
            ->where('nombre', $data['nombre'])
            ->where('id', '!=', $caja->id)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', __('Ya existe una caja con ese nombre en la sucursal seleccionada.'));
        }

        $caja->update($data);

        return redirect()
            ->route('cajas.index')
            ->with('success', __('messages.cash_register_updated'));
    }

    public function destroy(Caja $caja)
    {
        $this->verificarAccesoSucursal($caja->sucursal_id);
        $caja->delete();

        return redirect()
            ->route('cajas.index')
            ->with('success', __('messages.cash_register_deleted'));
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
