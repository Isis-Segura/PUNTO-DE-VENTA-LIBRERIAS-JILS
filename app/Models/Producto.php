<?php

namespace App\Models;

use App\Models\Concerns\TieneTraduccion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use TieneTraduccion;

    protected $table = 'productos';

    protected $fillable = [
        'sucursal_id',
        'categoria_id',
        'nombre',
        'nombre_en',
        'descripcion',
        'descripcion_en',
        'codigo',
        'autor',
        'editorial',
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

    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(Genero::class, 'genero_producto')->withTimestamps();
    }

    /** @deprecated usar generos() */
    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto')->withTimestamps();
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class);
    }

    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function getExistenciaAttribute(): int
    {
        return $this->inventario?->cantidad ?? 0;
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        $ruta = public_path('portadas/'.$this->imagen);
        $version = is_file($ruta) ? filemtime($ruta) : null;

        return asset('portadas/'.$this->imagen).($version ? '?v='.$version : '');
    }

    public function getGenerosListaAttribute(): string
    {
        $nombres = $this->relationLoaded('generos')
            ? $this->generos->map->nombre_traducido
            : $this->generos()->get()->map->nombre_traducido;

        return $nombres->filter()->implode(', ') ?: '—';
    }
}
