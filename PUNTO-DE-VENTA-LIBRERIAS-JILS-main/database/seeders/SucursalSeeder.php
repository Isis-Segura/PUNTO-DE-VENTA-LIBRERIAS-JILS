<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class SucursalSeeder extends Seeder
{
    public function run(): void
    {
        Sucursal::updateOrCreate(
            ['nombre' => 'Sucursal Centro'],
            [
                'direccion' => 'Av. Principal #123',
                'telefono' => '3121234567',
                'contacto' => 'centro@sispuntodeventa.com',
                'activa' => true,
            ]
        );
    }
}
