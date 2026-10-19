<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

/**
 * Datos de demostración opcionales (productos de ejemplo).
 * Ya NO crea usuarios gerente@pi.com ni cajero@pi.com.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $sucursal = Sucursal::where('nombre', 'Sucursal Centro')->first();

        if (! $sucursal) {
            return;
        }

        $categoriaId = Categoria::query()->value('id');

        $productosDemo = [
            ['nombre' => 'Cuaderno profesional 100 hojas', 'precio' => 35.00, 'cantidad' => 40],
            ['nombre' => 'Lápiz del número 2', 'precio' => 5.00, 'cantidad' => 100],
            ['nombre' => 'Bolígrafo azul', 'precio' => 8.50, 'cantidad' => 80],
            ['nombre' => 'Marcador para pizarrón', 'precio' => 15.00, 'cantidad' => 30],
            ['nombre' => 'Resistol 250g', 'precio' => 22.00, 'cantidad' => 3],
        ];

        foreach ($productosDemo as $item) {
            $producto = Producto::firstOrCreate(
                [
                    'sucursal_id' => $sucursal->id,
                    'nombre' => $item['nombre'],
                ],
                [
                    'categoria_id' => $categoriaId,
                    'precio' => $item['precio'],
                    'activo' => true,
                ]
            );

            Inventario::firstOrCreate(
                ['producto_id' => $producto->id],
                [
                    'cantidad' => $item['cantidad'],
                    'stock_minimo' => 5,
                ]
            );
        }
    }
}
