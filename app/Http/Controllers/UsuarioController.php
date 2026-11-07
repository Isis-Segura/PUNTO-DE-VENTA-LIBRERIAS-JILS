<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\UsuarioBaja;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TerminosCondicionesMail;

use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios del sistema.
     * - Administrador General: ve a todos los usuarios.
     * - Gerente: solo ve a los Cajeros de su propia sucursal (no puede ver
     *   a otros Gerentes, Administradores, ni cajeros de otras sucursales).
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = User::with(['role', 'sucursal'])->orderBy('name');

        // No mostrar al usuario que está usando la sesión actual
        $query->where('id', '!=', auth()->id());

        if (! auth()->user()->isAdmin()) {
            $this->limitarAlAlcanceDelGerente($query);
        }

        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $estado = $this->estadoDesdeBusqueda($q);

            $query->where(function ($sub) use ($q, $estado) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");

                // También se puede buscar por estado: activo / inactivo / pendiente
                // (en español o inglés), igual que las etiquetas de la columna Estado.
                if ($estado === 'pendiente') {
                    $sub->orWhere(fn ($w) => $w->where('activo', true)->whereNull('terminos_aceptados_at'));
                } elseif ($estado === 'activo') {
                    $sub->orWhere(fn ($w) => $w->where('activo', true)->whereNotNull('terminos_aceptados_at'));
                } elseif ($estado === 'inactivo') {
                    $sub->orWhere('activo', false);
                }
            });
        }

        $usuarios = $query->paginate(10)->withQueryString();

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
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            // Un Gerente o un Cajero necesitan sucursal para poder trabajar
            // (buscar/vender productos, ver su inventario, etc.). El
            // Administrador General normalmente no la necesita.
            'sucursal_id' => ['nullable', Rule::exists('sucursales', 'id')],
        ]);

        $this->autorizarRolYSucursal($data['role_id'], $data['sucursal_id'] ?? null);

        // Flujo de alta:
        //  1) Aquí solo se envía un correo de BIENVENIDA con un enlace a la página de
        //     términos y condiciones (no lleva usuario, contraseña ni rol).
        //  2) Cuando el usuario acepta en esa página (TerminosController), ahí mismo
        //     ve su correo, rol y contraseña.
        // La contraseña inicial se guarda CIFRADA (no en texto plano) para poder
        // mostrarla; se borra sola cuando el usuario cambia su contraseña (User::booted).
        $usuario = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'sucursal_id' => $data['sucursal_id'] ?? null,
            'activo' => true,
            'terminos_aceptados_at' => null,
            'credenciales_pendientes' => $data['password'],
        ]);

        $mensaje = __('messages.user_created');

        try {
            Mail::to($usuario->email)->send(
                new TerminosCondicionesMail($usuario, $usuario->urlAceptarTerminos())
            );
            $mensaje .= __('messages.user_terms_email_sent', ['email' => $usuario->email]);
        } catch (\Throwable $e) {
            report($e);
            // Mensaje corto para no romper la sesión / la vista
            $mensaje .= __('messages.user_terms_email_failed');
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', $mensaje);
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
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:120', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:8', 'max:64', 'confirmed'],
            'role_id' => ['required', Rule::exists('roles', 'id')],
            'sucursal_id' => ['nullable', Rule::exists('sucursales', 'id')],
            'activo' => ['required', 'boolean'],
        ]);

        $this->autorizarRolYSucursal($data['role_id'], $data['sucursal_id'] ?? null);

        // Un Gerente no puede ascenderse ni ascender a nadie a Administrador,
        // ni mover al usuario a una sucursal que no sea la suya.
        if (! auth()->user()->isAdmin() && (int) $data['role_id'] !== Role::where('slug', Role::CAJERO)->value('id')) {
            abort(403, __('messages.assign_role_permission'));
        }

        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        $usuario->role_id = $data['role_id'];
        $usuario->sucursal_id = $data['sucursal_id'] ?? null;
        $usuario->activo = $data['activo'];

        if (! empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);

            // Si la página de términos todavía va a mostrar la contraseña inicial
            // (no ha aceptado, o aceptó y no la ha cambiado), debe mostrar la nueva
            // y no la que se capturó al registrarlo.
            if (! $usuario->haAceptadoTerminos() || $usuario->tieneCredencialesIniciales()) {
                $usuario->credenciales_pendientes = $data['password'];
            }
        }

        $usuario->save();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', __('messages.user_updated'));
    }

    /**
     * Reenvía el correo de bienvenida (con un enlace nuevo a la página de términos)
     * por si el correo falló, se perdió o el enlace ya venció. Solo se puede mientras
     * el usuario NO haya aceptado los términos.
     */
    public function reenviarTerminos(User $usuario)
    {
        $this->verificarAccesoAUsuario($usuario);

        if ($usuario->haAceptadoTerminos()) {
            return back()->with('error', __('messages.welcome_nothing_to_resend'));
        }

        try {
            Mail::to($usuario->email)->send(
                new TerminosCondicionesMail($usuario, $usuario->urlAceptarTerminos())
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', __('messages.terms_resend_failed'));
        }

        return back()->with('success', __('messages.terms_resent', ['email' => $usuario->email]));
    }

    /**
     * "Eliminar" tiene dos pasos:
     *  1) Usuario dado de alta (aceptó los términos) y activo  -> solo se pone INACTIVO.
     *  2) Usuario inactivo (o que nunca aceptó) -> eliminación PERMANENTE.
     *
     * La eliminación permanente se bloquea si el usuario tiene ventas, porque la base
     * de datos borraría también esas ventas en cascada (se perdería el historial).
     * El Administrador General no puede eliminarse a sí mismo.
     */
    public function destroy(User $usuario)
    {
        $this->verificarAccesoAUsuario($usuario);

        if ($usuario->id === auth()->id()) {
            return back()->with('error', __('messages.cannot_delete_self'));
        }

        // Paso 1: dado de alta y activo -> solo se desactiva (puede reactivarse).
        if ($usuario->activo && $usuario->haAceptadoTerminos()) {
            $usuario->activo = false; // User::booted guarda "desactivado_at"
            $usuario->save();

            return redirect()
                ->route('admin.usuarios.index')
                ->with('success', __('messages.user_deactivated'));
        }

        // Paso 2: eliminación permanente.
        if (Venta::where('user_id', $usuario->id)->exists()) {
            return back()->with('error', __('messages.user_has_sales'));
        }

        $mensaje = __('messages.user_deleted');

        DB::transaction(function () use ($usuario, &$mensaje) {
            // Si era Gerente: desactivar su(s) sucursal(es) y quitar el enlace de gerente
            if ($usuario->isGerente()) {
                $sucursales = \App\Models\Sucursal::query()
                    ->where(function ($q) use ($usuario) {
                        $q->where('gerente_id', $usuario->id);
                        if ($usuario->sucursal_id) {
                            $q->orWhere('id', $usuario->sucursal_id);
                        }
                    })
                    ->get();

                foreach ($sucursales as $sucursal) {
                    $sucursal->activa = false;
                    if ((int) $sucursal->gerente_id === (int) $usuario->id) {
                        $sucursal->gerente_id = null;
                    }
                    $sucursal->save();
                }

                if ($sucursales->isNotEmpty()) {
                    $nombres = $sucursales->pluck('nombre')->implode(', ');
                    $mensaje .= __('messages.branch_deactivated_without_manager', ['branches' => $nombres]);
                }
            }

            // Se conserva la "salida definitiva" (y sus demás fechas) en el historial de
            // bajas. Quien nunca llegó a entrar (no aceptó los términos) no se registra.
            if ($usuario->haAceptadoTerminos()) {
                UsuarioBaja::create([
                    'user_id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'rol_slug' => $usuario->role?->slug,
                    'rol_nombre' => $usuario->role?->nombre,
                    'sucursal_id' => $usuario->sucursal_id,
                    'sucursal_nombre' => $usuario->sucursal?->nombre,
                    'ingreso_at' => $usuario->terminos_aceptados_at,
                    'desactivado_at' => $usuario->desactivado_at,
                    'reactivado_at' => $usuario->reactivado_at,
                    'eliminado_at' => now(),
                    'eliminado_por' => auth()->user()->name,
                ]);
            }

            $usuario->delete();
        });

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', $mensaje);
    }

    /**
     * Regresa a "Activo" a un usuario que estaba inactivo.
     */
    public function reactivar(User $usuario)
    {
        $this->verificarAccesoAUsuario($usuario);

        if ($usuario->activo) {
            return back()->with('error', __('messages.user_already_active'));
        }

        $usuario->activo = true; // User::booted guarda "reactivado_at"
        $usuario->save();

        return back()->with('success', __('messages.user_reactivated'));
    }

    /**
     * Historial de usuarios eliminados de forma permanente, con sus fechas.
     * - Administrador General: ve todas las bajas.
     * - Gerente: solo las bajas de cajeros de su sucursal.
     */
    public function bajas(Request $request)
    {
        $query = UsuarioBaja::query()->orderByDesc('eliminado_at');

        if (! auth()->user()->isAdmin()) {
            $query->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('rol_slug', Role::CAJERO);
        }

        $bajas = $query->paginate(10)->withQueryString();

        return view('admin.usuarios.bajas', compact('bajas'));
    }

    /**
     * Si lo escrito en el buscador es (el comienzo de) un estado, devuelve cuál:
     * 'pendiente' | 'activo' | 'inactivo' (español o inglés); si no, null.
     */
    private function estadoDesdeBusqueda(string $q): ?string
    {
        $q = Str::of($q)->lower()->ascii()->trim()->toString();

        if (mb_strlen($q) < 3) {
            return null;
        }

        $palabras = [
            'pendiente' => ['pendiente', 'pending'],
            'activo' => ['activo', 'active'],
            'inactivo' => ['inactivo', 'inactive'],
        ];

        foreach ($palabras as $estado => $lista) {
            foreach ($lista as $palabra) {
                if (str_starts_with($palabra, $q)) {
                    return $estado;
                }
            }
        }

        return null;
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
            abort(403, __('messages.manage_user_permission'));
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
            abort(422, __('messages.invalid_role'));
        }

        // Gerente y Cajero necesitan una sucursal asignada para poder operar
        // (buscar productos, vender, ver su inventario, etc.).
        if (in_array($rol->slug, [Role::GERENTE, Role::CAJERO], true) && ! $sucursalId) {
            abort(422, __('messages.branch_required_for_user'));
        }

        if (! auth()->user()->isAdmin()) {
            if ($rol->slug !== Role::CAJERO) {
                abort(403, __('messages.admin_role_only'));
            }

            if ($sucursalId !== auth()->user()->sucursal_id) {
                abort(403, __('messages.own_branch_only'));
            }
        }
    }
}
