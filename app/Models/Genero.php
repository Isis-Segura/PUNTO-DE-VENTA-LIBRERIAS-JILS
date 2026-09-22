<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genero extends Model
{
    protected $table = 'generos';

    protected $fillable = ['nombre', 'descripcion', 'nombre_en', 'descripcion_en'];

    public function getNombreTraducidoAttribute(): string
    {
        return app()->getLocale() === 'en' && filled($this->nombre_en)
            ? $this->nombre_en
            : $this->nombre;
    }

    public function getDescripcionTraducidaAttribute(): ?string
    {
        return app()->getLocale() === 'en' && filled($this->descripcion_en)
            ? $this->descripcion_en
            : $this->descripcion;
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'genero_producto')->withTimestamps();
    }
}
