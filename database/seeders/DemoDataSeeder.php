<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Role;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crea un gerente, un cajero y algunos productos de ejemplo en "Sucursal
 * Centro" para poder probar el flujo completo (login -> productos ->
 * inventario -> punto de venta) sin tener que capturar todo a mano.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $sucursal = Sucursal::where('nombre', 'Sucursal Centro')->first();

        if (! $sucursal) {
            return; // por si se corre este seeder sin haber corrido SucursalSeeder antes
        }

        $rolGerente = Role::where('slug', Role::GERENTE)->first();
        $rolCajero = Role::where('slug', Role::CAJERO)->first();

        $gerente = User::updateOrCreate(
            ['email' => 'gerente@pi.com'],
            [
                'name' => 'Gerente Sucursal Centro',
                'password' => Hash::make('password'),
                'role_id' => $rolGerente?->id,
                'sucursal_id' => $sucursal->id,
                'email_verified_at' => now(),
                'activo' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'cajero@pi.com'],
            [
                'name' => 'Cajero Sucursal Centro',
                'password' => Hash::make('password'),
                'role_id' => $rolCajero?->id,
                'sucursal_id' => $sucursal->id,
                'email_verified_at' => now(),
                'activo' => true,
            ]
        );

        $sucursal->update(['gerente_id' => $gerente->id]);

        $categoriaId = Categoria::query()->value('id');

        $productosDemo = [
            ['nombre' => 'Cuaderno profesional 100 hojas', 'precio' => 35.00, 'cantidad' => 40],
            ['nombre' => 'Lápiz del número 2', 'precio' => 5.00, 'cantidad' => 100],
            ['nombre' => 'Bolígrafo azul', 'precio' => 8.50, 'cantidad' => 80],
            ['nombre' => 'Marcador para pizarrón', 'precio' => 15.00, 'cantidad' => 30],
            ['nombre' => 'Resistol 250g', 'precio' => 22.00, 'cantidad' => 3], // a propósito, bajo en stock
        ];

        foreach ($productosDemo as $item) {
            $producto = Producto::updateOrCreate(
                ['sucursal_id' => $sucursal->id, 'nombre' => $item['nombre']],
                [
                    'categoria_id' => $categoriaId,
                    'precio' => $item['precio'],
                    'activo' => true,
                ]
            );

            Inventario::updateOrCreate(
                ['producto_id' => $producto->id],
                ['cantidad' => $item['cantidad'], 'stock_minimo' => 5]
            );
        }
    }
}
