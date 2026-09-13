<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'sucursal_id',
        'categoria_id',
        'nombre',
        'descripcion',
        'codigo',
        'precio',
        'imagen',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class);
    }

    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /**
     * Cantidad disponible actual (0 si por alguna razón no tiene registro de inventario).
     */
    public function getExistenciaAttribute(): int
    {
        return $this->inventario?->cantidad ?? 0;
    }

    /**
     * Solo productos con stock disponible mayor a cero.
     */
    public function scopeConStock($query)
    {
        return $query->whereHas('inventario', fn ($q) => $q->where('cantidad', '>', 0));
    }
}