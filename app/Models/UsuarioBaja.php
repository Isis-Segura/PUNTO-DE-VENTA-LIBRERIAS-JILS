<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Registro de un usuario eliminado de forma permanente (con las fechas que tenía),
 * para poder consultar su "salida definitiva" aunque ya no exista en users.
 */
class UsuarioBaja extends Model
{
    protected $table = 'usuarios_bajas';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'rol_slug',
        'rol_nombre',
        'sucursal_id',
        'sucursal_nombre',
        'ingreso_at',
        'desactivado_at',
        'reactivado_at',
        'eliminado_at',
        'eliminado_por',
    ];

    protected $casts = [
        'ingreso_at' => 'datetime',
        'desactivado_at' => 'datetime',
        'reactivado_at' => 'datetime',
        'eliminado_at' => 'datetime',
    ];
}
