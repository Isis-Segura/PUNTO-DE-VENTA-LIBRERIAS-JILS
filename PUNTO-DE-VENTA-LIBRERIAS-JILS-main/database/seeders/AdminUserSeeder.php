<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Crea un administrador SOLO si se define ADMIN_SEED_EMAIL y ADMIN_SEED_PASSWORD
 * en el .env. Ya no se usa el correo fijo admin@pi.com.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL');
        $password = env('ADMIN_SEED_PASSWORD');

        if (! $email || ! $password) {
            if (app()->runningInConsole()) {
                fwrite(STDOUT, "\n[AdminUserSeeder] Omitido: define ADMIN_SEED_EMAIL y ADMIN_SEED_PASSWORD en .env para crear el primer admin.\n\n");
            }

            return;
        }

        $adminRole = Role::where('slug', Role::ADMIN)->first();

        $admin = User::where('email', $email)->first();

        if ($admin) {
            $admin->forceFill([
                'role_id' => $adminRole?->id,
                'activo' => true,
            ])->save();

            return;
        }

        User::create([
            'name' => env('ADMIN_SEED_NAME', 'Administrador'),
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $adminRole?->id,
            'email_verified_at' => now(),
            'activo' => true,
        ]);

        if (app()->runningInConsole()) {
            fwrite(STDOUT, "\n[AdminUserSeeder] Administrador creado: {$email}\n\n");
        }
    }
}
