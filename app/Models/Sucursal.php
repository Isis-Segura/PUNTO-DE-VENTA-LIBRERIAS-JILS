<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'contacto',
        'activa',
        'gerente_id',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function gerente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }

    /**
     * Todos los usuarios (gerente y cajeros) asignados a esta sucursal.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'sucursal_id');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }
}