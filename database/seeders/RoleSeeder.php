<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['slug' => Role::ADMIN, 'nombre' => 'Administrador General'],
            ['slug' => Role::GERENTE, 'nombre' => 'Gerente de Sede'],
            ['slug' => Role::CAJERO, 'nombre' => 'Cajero'],
        ];

        foreach ($roles as $role) {
            // updateOrCreate evita duplicar los roles si el seeder se corre más de una vez
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
