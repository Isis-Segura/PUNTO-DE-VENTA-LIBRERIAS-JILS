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
        'nombre_en',
        'descripcion',
        'descripcion_en',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function getNombreTraducidoAttribute(): string
    {
        return app()->getLocale() === 'en' && filled($this->nombre_en)
            ? $this->nombre_en
            : __($this->nombre);
    }

    public function getDescripcionTraducidaAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->descripcion_en)
            ? $this->descripcion_en
            : $this->descripcion;
    }

    public function scopeBuscarBilingue($query, string $texto, array $extra = [])
    {
        return $query->where(function ($sub) use ($texto, $extra) {
            foreach (array_merge(['nombre', 'nombre_en', 'descripcion', 'descripcion_en'], $extra) as $indice => $columna) {
                $sub->{$indice === 0 ? 'where' : 'orWhere'}($columna, 'like', "%{$texto}%");
            }
        });
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
