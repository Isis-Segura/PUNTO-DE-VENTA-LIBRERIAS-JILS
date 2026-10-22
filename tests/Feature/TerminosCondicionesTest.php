<?php

namespace Tests\Feature;

use App\Mail\TerminosCondicionesMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TerminosCondicionesTest extends TestCase
{
    use RefreshDatabase;

    private function rolAdmin(): Role
    {
        return Role::create(['slug' => Role::ADMIN, 'nombre' => 'Administrador General']);
    }

    private function usuarioPendiente(Role $rol, string $password = 'ClaveTemporal123'): User
    {
        return User::factory()->create([
            'role_id' => $rol->id,
            'terminos_aceptados_at' => null,
            'credenciales_pendientes' => $password,
        ]);
    }

    public function test_welcome_email_has_the_link_but_no_credentials(): void
    {
        $usuario = new User(['name' => 'Usuario de prueba', 'email' => 'nuevo@example.com']);

        $contenido = (new TerminosCondicionesMail($usuario, 'https://example.test/terminos/1'))->render();

        $this->assertStringContainsString('Usuario de prueba', $contenido);
        $this->assertStringContainsString('Ver términos y condiciones', $contenido);
        $this->assertStringContainsString('https://example.test/terminos/1', $contenido);
        // El correo no muestra los datos de acceso ni el texto de los términos.
        $this->assertStringNotContainsString('nuevo@example.com', $contenido);
        $this->assertStringNotContainsString('Contraseña', $contenido);
    }

    public function test_creating_a_user_sends_only_the_welcome_email_and_stores_password_encrypted(): void
    {
        Mail::fake();

        $rol = $this->rolAdmin();
        $admin = User::factory()->create(['role_id' => $rol->id]);

        $this->actingAs($admin)->post(route('admin.usuarios.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'ClaveTemporal123',
            'password_confirmation' => 'ClaveTemporal123',
            'role_id' => $rol->id,
        ])->assertRedirect(route('admin.usuarios.index'));

        Mail::assertSent(TerminosCondicionesMail::class, fn ($m) => $m->hasTo('nuevo@example.com'));
        Mail::assertSentCount(1);

        $usuario = User::where('email', 'nuevo@example.com')->firstOrFail();
        $this->assertNull($usuario->terminos_aceptados_at);
        $this->assertSame('ClaveTemporal123', $usuario->credenciales_pendientes);

        // En la base de datos NO debe quedar en texto plano.
        $crudo = DB::table('users')->where('email', 'nuevo@example.com')->value('credenciales_pendientes');
        $this->assertStringNotContainsString('ClaveTemporal123', (string) $crudo);
    }

    public function test_page_requires_a_valid_signature(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());

        $this->get('/terminos/'.$usuario->id)->assertForbidden();
        $this->post('/terminos/'.$usuario->id)->assertForbidden();
    }

    public function test_expired_link_is_rejected(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->travel(User::DIAS_VIGENCIA_TERMINOS + 1)->days();

        $this->get($url)->assertForbidden();
    }

    public function test_pending_user_sees_the_terms_with_both_buttons_and_no_credentials(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());

        $this->get($usuario->urlAceptarTerminos())
            ->assertOk()
            ->assertSee('No compartirá sus credenciales con nadie.')
            ->assertSee('Aceptar términos')
            ->assertSee('Rechazar términos')
            // Pantalla que aparece al rechazar (oculta hasta que se presiona el botón).
            ->assertSee('Cerrando términos y condiciones')
            ->assertDontSee('ClaveTemporal123')
            ->assertDontSee($usuario->email)
            ->assertDontSee('Administrador General');

        // Solo abrir el enlace no acepta nada.
        $this->assertNull($usuario->fresh()->terminos_aceptados_at);
    }

    public function test_accepting_saves_the_decision_and_then_shows_the_access_data(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url)->assertRedirect();

        $usuario->refresh();
        $this->assertNotNull($usuario->terminos_aceptados_at);

        $this->get($url)
            ->assertOk()
            ->assertSee($usuario->email)
            ->assertSee('Administrador General')
            ->assertSee('ClaveTemporal123')
            // Se pueden volver a leer los términos, pero ya no hay botones para decidir.
            ->assertSee('No compartirá sus credenciales con nadie.')
            ->assertDontSee('id="accept"', false)
            ->assertDontSee('id="reject"', false)
            ->assertDontSee('Cerrando términos y condiciones');
    }

    public function test_the_access_data_keeps_showing_every_time_the_link_is_opened(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url);

        $this->get($url)->assertSee('ClaveTemporal123');
        $this->get($url)->assertSee('ClaveTemporal123');
    }

    public function test_acceptance_cannot_be_undone_or_rewritten(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url);
        $primera = $usuario->fresh()->terminos_aceptados_at;

        $this->travel(2)->hours();
        $this->post($url);

        $this->assertTrue($primera->equalTo($usuario->fresh()->terminos_aceptados_at));
    }

    public function test_changing_the_password_removes_the_stored_initial_password(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();
        $this->post($url);

        // Como hace ProfileController o el reset de contraseña de Laravel.
        $usuario->password = Hash::make('OtraClave98765');
        $usuario->save();

        $this->assertFalse($usuario->fresh()->tieneCredencialesIniciales());

        $this->get($url)
            ->assertOk()
            ->assertSee('ya fue cambiada')
            ->assertDontSee('ClaveTemporal123')
            ->assertDontSee('OtraClave98765');
    }

    public function test_admin_reassigning_the_initial_password_keeps_it_available(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());

        // Como hace UsuarioController@update cuando el admin cambia la contraseña.
        $usuario->password = Hash::make('ClaveNueva12345');
        $usuario->credenciales_pendientes = 'ClaveNueva12345';
        $usuario->save();

        $this->assertSame('ClaveNueva12345', $usuario->fresh()->credenciales_pendientes);
    }
}
