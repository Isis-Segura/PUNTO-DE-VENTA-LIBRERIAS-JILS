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

#[Fillable(['name', 'email', 'password', 'role_id', 'sucursal_id', 'activo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
