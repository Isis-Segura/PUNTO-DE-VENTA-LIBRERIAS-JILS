<?php

namespace App\Models\Concerns;

trait TieneTraduccion
{
    public function getNombreTraducidoAttribute(): string
    {
        return app()->getLocale() === 'en' && filled($this->nombre_en)
            ? $this->nombre_en
            : (string) $this->nombre;
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
}
