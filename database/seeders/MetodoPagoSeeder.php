<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Efectivo', 'Tarjeta'] as $nombre) {
            MetodoPago::updateOrCreate(['nombre' => $nombre]);
        }

        // Eliminar métodos que ya no se usan
        MetodoPago::whereNotIn('nombre', ['Efectivo', 'Tarjeta'])->delete();
    }
}
