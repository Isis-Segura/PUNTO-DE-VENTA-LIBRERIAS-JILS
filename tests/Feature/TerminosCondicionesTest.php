<?php

namespace Tests\Feature;

use App\Mail\TerminosCondicionesMail;
use App\Models\Role;
use App\Models\TermsRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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

    public function test_accepting_saves_the_decision_and_shows_the_access_data_once(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url)->assertRedirect($url);

        $usuario->refresh();
        $this->assertNotNull($usuario->terminos_aceptados_at);
        $this->assertNotNull($usuario->terminos_enlace_usado_at);

        // La redirección inmediata muestra (una vez) los datos de acceso, sin botones.
        $this->get($url)
            ->assertOk()
            ->assertSee($usuario->email)
            ->assertSee('Administrador General')
            ->assertSee('ClaveTemporal123')
            ->assertSee('No compartirá sus credenciales con nadie.')
            ->assertDontSee('id="accept"', false)
            ->assertDontSee('id="reject"', false);

        // Al volver a abrir el enlace ya no está disponible.
        $this->get($url)->assertStatus(410)
            ->assertSee('Este enlace ya no está disponible')
            ->assertDontSee('ClaveTemporal123');
    }

    public function test_rejecting_spends_the_link_without_saving_acceptance(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url, ['decision' => 'reject'], ['Accept' => 'application/json'])->assertNoContent();

        $this->assertNull($usuario->fresh()->terminos_aceptados_at);
        $this->assertNotNull($usuario->fresh()->terminos_enlace_usado_at);

        $this->get($url)->assertStatus(410)->assertSee('Solicitar ver los términos de nuevo');
    }

    public function test_a_spent_link_cannot_accept_afterwards(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url, ['decision' => 'reject'], ['Accept' => 'application/json']);
        $this->post($url);

        $this->assertNull($usuario->fresh()->terminos_aceptados_at);
    }

    public function test_opening_the_page_without_deciding_does_not_spend_the_link(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->get($url)->assertOk();
        $this->get($url)->assertOk()->assertSee('Aceptar términos');
    }

    public function test_user_can_request_to_see_the_terms_again_only_once_while_pending(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();
        $this->post($url, ['decision' => 'reject'], ['Accept' => 'application/json']);

        $solicitar = URL::temporarySignedRoute(
            'terminos.solicitar', now()->addDay(), ['usuario' => $usuario->id]
        );

        $this->post($solicitar)->assertStatus(410)->assertSee('Solicitud enviada');
        $this->post($solicitar);

        $this->assertSame(1, TermsRequest::where('user_id', $usuario->id)->pending()->count());
        $this->get($url)->assertSee('Solicitud pendiente');

        $this->post('/terminos/'.$usuario->id.'/solicitar')->assertForbidden();
    }

    public function test_admin_approving_resends_the_email_with_a_new_single_use_link(): void
    {
        Mail::fake();

        $rol = $this->rolAdmin();
        $admin = User::factory()->create(['role_id' => $rol->id]);
        $usuario = $this->usuarioPendiente($rol);
        $viejo = $usuario->urlAceptarTerminos();
        $this->post($viejo, ['decision' => 'reject'], ['Accept' => 'application/json']);

        $solicitud = TermsRequest::create(['user_id' => $usuario->id, 'status' => 'pending']);

        $this->actingAs($admin)
            ->post(route('admin.terms-requests.approve', $solicitud))
            ->assertSessionHas('success');

        $this->assertSame('attended', $solicitud->fresh()->status);
        $this->assertSame($admin->id, $solicitud->fresh()->attended_by);

        $nuevo = null;
        Mail::assertSent(TerminosCondicionesMail::class, function ($m) use (&$nuevo, $usuario) {
            $nuevo = $m->urlAceptar;

            return $m->hasTo($usuario->email);
        });

        // El enlace viejo sigue sin servir; el nuevo funciona (y es de un solo uso).
        $this->get($viejo)->assertStatus(410);
        $this->get($nuevo)->assertOk()->assertSee('Aceptar términos');
        $this->post($nuevo)->assertRedirect();
        $this->assertNotNull($usuario->fresh()->terminos_aceptados_at);
        $this->get($nuevo)->assertOk(); // vista inmediata tras aceptar
        $this->get($nuevo)->assertStatus(410);
    }

    public function test_already_accepted_user_gets_a_temporary_single_use_view(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $this->post($usuario->urlAceptarTerminos());
        $fecha = $usuario->fresh()->terminos_aceptados_at;

        $nuevo = $usuario->fresh()->urlAceptarTerminos();

        $this->get($nuevo)->assertOk()->assertDontSee('id="accept"', false)->assertSee('ClaveTemporal123');
        $this->get($nuevo)->assertStatus(410);
        $this->assertTrue($fecha->equalTo($usuario->fresh()->terminos_aceptados_at));
    }

    public function test_only_admin_sees_requests_page_with_both_types(): void
    {
        $rol = $this->rolAdmin();
        $admin = User::factory()->create(['role_id' => $rol->id]);
        $usuario = $this->usuarioPendiente($rol);
        TermsRequest::create(['user_id' => $usuario->id, 'status' => 'pending']);

        $this->actingAs($admin)->get(route('admin.solicitudes.index'))
            ->assertOk()->assertSee('Cambio de contraseña')->assertSee('Términos y condiciones');

        $this->get(route('admin.solicitudes.index', ['tipo' => 'terminos']))
            ->assertOk()->assertSee('Aprobar y reenviar correo');

        $this->get(route('admin.password-requests.index'))
            ->assertRedirect(route('admin.solicitudes.index', ['tipo' => 'contrasena']));
    }

    public function test_acceptance_cannot_be_undone_or_rewritten(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $url = $usuario->urlAceptarTerminos();

        $this->post($url);
        $primera = $usuario->fresh()->terminos_aceptados_at;

        $this->travel(2)->hours();
        $this->post($usuario->fresh()->urlAceptarTerminos());

        $this->assertTrue($primera->equalTo($usuario->fresh()->terminos_aceptados_at));
    }

    public function test_changing_the_password_removes_the_stored_initial_password(): void
    {
        $usuario = $this->usuarioPendiente($this->rolAdmin());
        $this->post($usuario->urlAceptarTerminos());

        // Como hace ProfileController o el reset de contraseña de Laravel.
        $usuario->password = Hash::make('OtraClave98765');
        $usuario->save();

        $this->assertFalse($usuario->fresh()->tieneCredencialesIniciales());

        $this->get($usuario->fresh()->urlAceptarTerminos())
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
