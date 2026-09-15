<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios del sistema.
     * - Administrador General: ve a todos los usuarios.
     * - Gerente: solo ve a los Cajeros de su propia sucursal (no puede ver
     *   a otros Gerentes, Administradores, ni cajeros de otras sucursales).
     */
    public function index()
    {
        $query = User::with(['role', 'sucursal'])->orderBy('name');

        if (! auth()->user()->isAdmin()) {
            $this->limitarAlAlcanceDelGerente($query);
        }

        $usuarios = $query->paginate(10);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario para registrar un nuevo usuario (Gerente o Cajero).
     */
    public function create()
    {
        $roles = $this->rolesAsignables();
        $sucursales = $this->sucursalesAsignables();

        return view('admin.usuarios.create', compact('roles', 'sucursales'));
    }

    /**
     * Guarda el nuevo usuario.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            // Un Gerente o un Cajero necesitan sucursal para poder trabajar
            // (buscar/vender productos, ver su inventario, etc.). El
            // Administrador General normalmente no la necesita.
            'sucursal_id' => ['nullable', Rule::exists('sucursales', 'id')],
        ]);

        $this->autorizarRolYSucursal($data['role_id'], $data['sucursal_id'] ?? null);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'sucursal_id' => $data['sucursal_id'] ?? null,
            'activo' => true,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Formulario para editar un usuario existente.
     */
    public function edit(User $usuario)
    {
        $this->verificarAccesoAUsuario($usuario);

        $roles = $this->rolesAsignables();
        $sucursales = $this->sucursalesAsignables();

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'sucursales'));
    }

    /**
     * Actualiza los datos de un usuario (rol, sucursal, nombre, estado activo/inactivo).
     * La contraseña solo se cambia si se llena el campo.
     */
    public function update(Request $request, User $usuario)
    {
        $this->verificarAccesoAUsuario($usuario);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            'sucursal_id' => ['nullable', Rule::exists('sucursales', 'id')],
            'activo' => ['required', 'boolean'],
        ]);

        $this->autorizarRolYSucursal($data['role_id'], $data['sucursal_id'] ?? null);

        // Un Gerente no puede ascenderse ni ascender a nadie a Administrador,
        // ni mover al usuario a una sucursal que no sea la suya.
        if (! auth()->user()->isAdmin() && (int) $data['role_id'] !== Role::where('slug', Role::CAJERO)->value('id')) {
            abort(403, 'No tienes permiso para asignar ese rol.');
        }

        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        $usuario->role_id = $data['role_id'];
        $usuario->sucursal_id = $data['sucursal_id'] ?? null;
        $usuario->activo = $data['activo'];

        if (! empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }

        $usuario->save();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario. El Administrador General no puede eliminarse a sí mismo.
     */
    public function destroy(User $usuario)
    {
        $this->verificarAccesoAUsuario($usuario);

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Roles que el usuario autenticado puede asignar al crear/editar.
     * - Administrador General: todos los roles.
     * - Gerente: únicamente Cajero (solo puede dar de alta cajeros de su
     *   propia sucursal, según el protocolo del proyecto).
     */
    private function rolesAsignables()
    {
        if (auth()->user()->isAdmin()) {
            return Role::orderBy('nombre')->get();
        }

        return Role::where('slug', Role::CAJERO)->get();
    }

    /**
     * Sucursales que se pueden ofrecer en el selector del formulario.
     * - Administrador General: todas.
     * - Gerente: únicamente la suya (ya viene preseleccionada y bloqueada
     *   del lado de la vista).
     */
    private function sucursalesAsignables()
    {
        if (auth()->user()->isAdmin()) {
            return Sucursal::orderBy('nombre')->get();
        }

        return Sucursal::where('id', auth()->user()->sucursal_id)->get();
    }

    /**
     * Limita el listado de usuarios a los Cajeros de la sucursal del Gerente.
     */
    private function limitarAlAlcanceDelGerente($query): void
    {
        $user = auth()->user();

        $query->where('sucursal_id', $user->sucursal_id)
            ->whereHas('role', fn ($q) => $q->where('slug', Role::CAJERO));
    }

    /**
     * Corta el acceso si un Gerente intenta ver/editar/eliminar a alguien
     * que no sea un Cajero de su propia sucursal (ej. manipulando la URL).
     */
    private function verificarAccesoAUsuario(User $usuario): void
    {
        if (auth()->user()->isAdmin()) {
            return;
        }

        $esCajeroDeSuSucursal = $usuario->hasRole(Role::CAJERO)
            && $usuario->sucursal_id === auth()->user()->sucursal_id;

        if (! $esCajeroDeSuSucursal) {
            abort(403, 'No tienes permiso para administrar a este usuario.');
        }
    }

    /**
     * Valida coherencia entre rol y sucursal, y que un Gerente no intente
     * asignar sucursales ajenas a la suya.
     */
    private function autorizarRolYSucursal(int $roleId, ?int $sucursalId): void
    {
        $rol = Role::find($roleId);

        if (! $rol) {
            abort(422, 'Rol inválido.');
        }

        // Gerente y Cajero necesitan una sucursal asignada para poder operar
        // (buscar productos, vender, ver su inventario, etc.).
        if (in_array($rol->slug, [Role::GERENTE, Role::CAJERO], true) && ! $sucursalId) {
            abort(422, 'Selecciona la sucursal a la que pertenecerá este usuario.');
        }

        if (! auth()->user()->isAdmin()) {
            if ($rol->slug !== Role::CAJERO) {
                abort(403, 'Solo el Administrador General puede asignar ese rol.');
            }

            if ($sucursalId !== auth()->user()->sucursal_id) {
                abort(403, 'Solo puedes asignar usuarios a tu propia sucursal.');
            }
        }
    }
}
