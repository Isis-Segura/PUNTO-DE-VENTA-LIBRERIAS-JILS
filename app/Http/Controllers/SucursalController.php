<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
    {
        $sucursales = Sucursal::with('gerente')->orderBy('nombre')->paginate(10);

        return view('sucursales.index', compact('sucursales'));
    }

    public function create()
    {
        $gerentes = $this->gerentesDisponibles();

        return view('sucursales.create', compact('gerentes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'contacto' => ['nullable', 'string', 'max:150'],
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
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'contacto' => ['nullable', 'string', 'max:150'],
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
