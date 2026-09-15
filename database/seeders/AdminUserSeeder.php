<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Antes, este seeder usaba updateOrCreate con la contraseña incluida en
     * los datos a actualizar. Eso significa que si alguien vuelve a correr
     * `php artisan db:seed` en producción (por accidente o de rutina), la
     * contraseña del admin se resetea sola a "password" cada vez.
     *
     * Ahora: si el usuario admin@pi.com YA existe, solo nos aseguramos de que
     * tenga el rol correcto y esté activo, pero NUNCA tocamos su contraseña.
     * La contraseña solo se establece la primera vez que se crea la cuenta.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', Role::ADMIN)->first();

        $admin = User::where('email', 'admin@pi.com')->first();

        if ($admin) {
            $admin->forceFill([
                'role_id' => $adminRole?->id,
                'activo' => true,
            ])->save();

            return;
        }

        // Contraseña inicial: se puede fijar por variable de entorno
        // (ADMIN_SEED_PASSWORD) para entornos de producción; si no se
        // define, se genera una aleatoria y se muestra una sola vez en
        // consola (nunca se guarda en texto plano en el repositorio).
        $password = env('ADMIN_SEED_PASSWORD') ?: Str::password(12);

        User::create([
            'name' => 'Administrador General',
            'email' => 'admin@pi.com',
            'password' => Hash::make($password),
            'role_id' => $adminRole?->id,
            'email_verified_at' => now(),
            'activo' => true,
        ]);

        if (! env('ADMIN_SEED_PASSWORD') && app()->runningInConsole()) {
            fwrite(STDOUT, "\n[AdminUserSeeder] Usuario admin@pi.com creado con contraseña temporal: {$password}\n");
            fwrite(STDOUT, "[AdminUserSeeder] Guárdala y cámbiala después de tu primer login.\n\n");
        }
    }
}
