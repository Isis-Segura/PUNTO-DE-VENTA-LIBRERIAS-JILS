<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventarios';

    protected $fillable = [
        'producto_id',
        'cantidad',
        'stock_minimo',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * true si la cantidad actual ya llegó (o está por debajo) del mínimo configurado.
     */
    public function getBajoInventarioAttribute(): bool
    {
        return $this->cantidad <= $this->stock_minimo;
    }
}
