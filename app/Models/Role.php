<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['slug', 'nombre'];

    // Slugs válidos según el protocolo del proyecto
    public const ADMIN = 'admin';
    public const GERENTE = 'gerente';
    public const CAJERO = 'cajero';

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
