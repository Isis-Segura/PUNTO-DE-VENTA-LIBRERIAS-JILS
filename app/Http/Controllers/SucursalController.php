<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Role;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index(Request $request)
    {
        // Gerente: ir directo a su sucursal (sin listado/resumen)
        $user = auth()->user();
        if ($user->isGerente() && $user->sucursal_id) {
            return redirect()->route('sucursales.show', $user->sucursal_id);
        }

        $query = Sucursal::with('gerente')
            ->withCount([
                'productos',
                'productos as bajo_stock_count' => function ($q) {
                    $q->whereHas('inventario', fn ($i) => $i->whereColumn('cantidad', '<=', 'stock_minimo'));
                },
            ])
            ->orderBy('nombre');

        // El Gerente solo debe ver su propia sucursal en el listado.
        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null) {
            $query->whereIn('id', $ids);
        }

        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('direccion', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        // Contadores para las tarjetas de resumen (sobre el total, no solo la página actual).
        $totalSucursales = (clone $query)->count();
        $activas = (clone $query)->where('activa', true)->count();
        $inactivas = $totalSucursales - $activas;

        $sucursales = $query->paginate(9)->withQueryString();

        return view('sucursales.index', compact('sucursales', 'totalSucursales', 'activas', 'inactivas'));
    }

    /**
     * Detalle de una sucursal: sus datos y el inventario de sus productos.
     * Sustituye al antiguo módulo independiente de "Inventario".
     */
    public function show(Request $request, Sucursal $sucursal)
    {
        $ids = auth()->user()->sucursalIdsPermitidas();

        if ($ids !== null && ! in_array($sucursal->id, $ids, true)) {
            abort(403, 'No tienes permiso para ver esta sucursal.');
        }

        $query = Inventario::with(['producto.categoria'])
            ->whereHas('producto', fn ($q) => $q->where('sucursal_id', $sucursal->id));

        if ($request->boolean('bajo_stock')) {
            $query->whereColumn('cantidad', '<=', 'stock_minimo');
        }

        $inventarios = $query->orderBy('cantidad')->paginate(15)->withQueryString();

        return view('sucursales.show', compact('sucursal', 'inventarios'));
    }

    public function create()
    {
        $gerentes = $this->gerentesDisponibles();

        return view('sucursales.create', compact('gerentes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'contacto' => ['nullable', 'string', 'max:100'],
            'gerente_id' => ['nullable', 'exists:users,id'],
            'activa' => ['required', 'boolean'],
        ]);

        $sucursal = Sucursal::create($data);

        // Si se asignó un gerente, lo enlazamos también desde su lado (users.sucursal_id)
        if (! empty($data['gerente_id'])) {
            User::where('id', $data['gerente_id'])->update(['sucursal_id' => $sucursal->id]);
        }

        return redirect()
            ->route('sucursales.index')
            ->with('success', 'Sucursal creada correctamente.');
    }

    public function edit(Sucursal $sucursal)
    {
        $gerentes = $this->gerentesDisponibles($sucursal->gerente_id);

        return view('sucursales.edit', compact('sucursal', 'gerentes'));
    }

    public function update(Request $request, Sucursal $sucursal)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'contacto' => ['nullable', 'string', 'max:100'],
            'gerente_id' => ['nullable', 'exists:users,id'],
            'activa' => ['required', 'boolean'],
        ]);

        // Si el gerente cambió, liberamos al anterior (para que quede disponible en otra sucursal)
        if ($sucursal->gerente_id && $sucursal->gerente_id != ($data['gerente_id'] ?? null)) {
            User::where('id', $sucursal->gerente_id)->update(['sucursal_id' => null]);
        }

        $sucursal->update($data);

        if (! empty($data['gerente_id'])) {
            User::where('id', $data['gerente_id'])->update(['sucursal_id' => $sucursal->id]);
        }

        return redirect()
            ->route('sucursales.index')
            ->with('success', 'Sucursal actualizada correctamente.');
    }

    public function destroy(Sucursal $sucursal)
    {
        if ($sucursal->productos()->exists()) {
            return back()->with('error', 'No puedes eliminar una sucursal que ya tiene productos registrados. Desactívala en su lugar.');
        }

        $sucursal->delete();

        return redirect()
            ->route('sucursales.index')
            ->with('success', 'Sucursal eliminada correctamente.');
    }

    /**
     * Usuarios con rol Gerente que pueden asignarse a una sucursal:
     * los que no tienen sucursal asignada todavía, más el gerente actual
     * (para que siga apareciendo seleccionado al editar).
     */
    private function gerentesDisponibles(?int $gerenteActualId = null)
    {
        return User::whereHas('role', fn ($q) => $q->where('slug', Role::GERENTE))
            ->where(function ($q) use ($gerenteActualId) {
                $q->whereNull('sucursal_id');

                if ($gerenteActualId) {
                    $q->orWhere('id', $gerenteActualId);
                }
            })
            ->orderBy('name')
            ->get();
    }
}
