<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'sucursal_id',
        'nombre',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
