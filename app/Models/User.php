<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;

#[Fillable(['name', 'email', 'password', 'role_id', 'sucursal_id', 'activo', 'terminos_aceptados_at', 'credenciales_pendientes'])]
#[Hidden(['password', 'remember_token', 'credenciales_pendientes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Días que dura vigente el enlace del correo de bienvenida (página de términos
     * y, una vez aceptados, datos de acceso). Pasado ese tiempo el administrador
     * puede reenviar el correo desde Usuarios.
     */
    public const DIAS_VIGENCIA_TERMINOS = 30;

    /**
     * Si cambia la contraseña (el propio usuario, el flujo de "olvidé mi contraseña"
     * o el administrador sin reasignar la inicial), la contraseña inicial guardada
     * ya no es válida: se borra para no mostrarla ni conservarla de más.
     */
    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if (! $user->exists || ! $user->isDirty('password')) {
                return;
            }

            // Comparación en crudo (sin descifrar): si en este mismo guardado no se
            // reasignó la contraseña inicial, se limpia.
            $actual = $user->getAttributes()['credenciales_pendientes'] ?? null;

            if ($actual === $user->getRawOriginal('credenciales_pendientes')) {
                $user->setAttribute('credenciales_pendientes', null);
            }
        });
    }

    /**
     * Rol al que pertenece el usuario (Administrador General, Gerente, Cajero).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Revisa si el usuario tiene el rol indicado.
     * Uso: $user->hasRole('admin'), $user->hasRole(Role::ADMIN)
     */
    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function isGerente(): bool
    {
        return $this->hasRole(Role::GERENTE);
    }

    public function isCajero(): bool
    {
        return $this->hasRole(Role::CAJERO);
    }

    /**
     * ¿El usuario ya aceptó los Términos y Condiciones (clic en el correo)?
     */
    public function haAceptadoTerminos(): bool
    {
        return $this->terminos_aceptados_at !== null;
    }

    /**
     * ¿Todavía hay una contraseña inicial guardada (cifrada) para mostrarle al
     * usuario en la página de términos? Deja de haberla cuando él la cambia.
     */
    public function tieneCredencialesIniciales(): bool
    {
        return ! empty($this->getAttributes()['credenciales_pendientes'] ?? null);
    }

    /**
     * Contraseña inicial descifrada, o null si no existe o no se puede descifrar
     * (por ejemplo, si cambió la APP_KEY).
     */
    public function credencialesIniciales(): ?string
    {
        try {
            return $this->credenciales_pendientes ?: null;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Enlace firmado (a prueba de manipulación y con vencimiento) que va en el
     * correo de bienvenida y lleva a la página de términos y condiciones.
     */
    public function urlAceptarTerminos(): string
    {
        return URL::temporarySignedRoute(
            'terminos.show',
            now()->addDays(self::DIAS_VIGENCIA_TERMINOS),
            ['usuario' => $this->getKey()]
        );
    }

    /**
     * Nombre del rol para mostrarlo en los correos.
     */
    public function nombreDeRol(): string
    {
        $rol = $this->role;

        return $rol ? ($rol->nombre ?? $rol->name ?? 'Usuario') : 'Usuario';
    }

    /**
     * Sucursal a la que pertenece el usuario (Gerente o Cajero).
     * El Administrador General normalmente no tiene sucursal asignada.
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * IDs de sucursales que el usuario puede consultar/administrar.
     * - Administrador General: null significa "todas" (sin filtro).
     * - Gerente / Cajero: solo su propia sucursal.
     */
    public function sucursalIdsPermitidas(): ?array
    {
        if ($this->isAdmin()) {
            return null;
        }

        return $this->sucursal_id ? [$this->sucursal_id] : [];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    /**
     * URL del enlace "Perfil" del menú de usuario de AdminLTE
     * (cambiar contraseña).
     */
    public function adminlte_profile_url(): string
    {
        return route('profile.password.edit');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terminos_aceptados_at' => 'datetime',
            // Contraseña inicial: se guarda cifrada con APP_KEY y se muestra solo después
            // de aceptar los términos. Se borra cuando el usuario cambia su contraseña.
            'credenciales_pendientes' => 'encrypted',
        ];
    }
}
