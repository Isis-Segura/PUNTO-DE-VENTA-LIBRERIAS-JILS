<?php

namespace App\Models;

use App\Models\Concerns\TieneTraduccion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory, TieneTraduccion;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'nombre_en',
        'descripcion',
        'descripcion_en',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function productosMany(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'categoria_producto')->withTimestamps();
    }
}
