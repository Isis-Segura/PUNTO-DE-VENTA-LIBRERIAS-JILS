<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genero extends Model
{
    protected $table = 'generos';

    protected $fillable = ['nombre', 'descripcion'];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'genero_producto')->withTimestamps();
    }
}
