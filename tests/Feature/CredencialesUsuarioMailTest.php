<?php

namespace Tests\Feature;

use App\Mail\CredencialesUsuarioMail;
use App\Models\User;
use Tests\TestCase;

class CredencialesUsuarioMailTest extends TestCase
{
    public function test_credentials_email_shows_user_role_and_password_in_order(): void
    {
        $usuario = new User([
            'name' => 'Usuario de prueba',
            'email' => 'nuevo@example.com',
        ]);

        $contenido = (new CredencialesUsuarioMail($usuario, 'ClaveTemporal123', 'Cajero'))->render();

        $usuarioEmail = strpos($contenido, 'nuevo@example.com');
        $rol = strpos($contenido, 'Cajero');
        $contrasena = strpos($contenido, 'ClaveTemporal123');

        $this->assertNotFalse($usuarioEmail);
        $this->assertNotFalse($rol);
        $this->assertNotFalse($contrasena);
        $this->assertTrue($usuarioEmail < $rol);
        $this->assertTrue($rol < $contrasena);
    }

    public function test_terms_are_no_longer_inside_the_credentials_email(): void
    {
        // Los términos se envían (y se aceptan) ANTES, en TerminosCondicionesMail.
        $usuario = new User(['name' => 'Usuario de prueba', 'email' => 'nuevo@example.com']);

        $contenido = (new CredencialesUsuarioMail($usuario, 'ClaveTemporal123', 'Cajero'))->render();

        $this->assertStringNotContainsString('Términos y Condiciones – Sistema POS Librería JILS', $contenido);
    }
}
