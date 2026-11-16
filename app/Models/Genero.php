<?php

namespace App\Models;

use App\Models\Concerns\TieneTraduccion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genero extends Model
{
    use TieneTraduccion;

    protected $table = 'generos';

    protected $fillable = ['nombre', 'descripcion', 'nombre_en', 'descripcion_en'];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'genero_producto')->withTimestamps();
    }
}
