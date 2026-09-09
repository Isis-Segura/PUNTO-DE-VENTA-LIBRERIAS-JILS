<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_login_page_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Librería JILS');
        $response->assertSee('Punto de Venta');
    }

    public function test_login_with_invalid_credentials_returns_error(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'invalido@test.com',
            'password' => 'claveincorrecta',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_successful_admin_login_redirects_to_admin_panel(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@pi.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }
}
