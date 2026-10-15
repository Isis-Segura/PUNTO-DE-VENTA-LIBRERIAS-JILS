<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'role_id' => 1,
                'name' => 'Johiel',
                'email' => 'joshuagabrieldm@gmail.com',
                'password' => '$2y$12$Twc5Vg6UixhrtcUr7mXK/.h.LiGOwy6sTNaW53SljPiOyFvPTe.5.',
                'activo' => 1,
            ],
            [
                'id' => 2,
                'role_id' => 1,
                'name' => 'Administrador General',
                'email' => 'admin@pi.com',
                'password' => '$2y$12$TXVXhuiewnjhsJP3BE6F5eGFzq9ZACMiEtuEJjaGM9ERHR1sA5J32',
                'activo' => 1,
            ],
            [
                'id' => 3,
                'role_id' => 2,
                'name' => 'Ariel',
                'email' => 'ari@gamil.com',
                'password' => '$2y$12$KaI5KLX0zo7Ijh0/rB1e/OsBjlTjstbABU/DA.vTtabxRVs4UvYDq',
                'activo' => 1,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
