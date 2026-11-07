<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\UsuarioBaja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuariosBajaTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $rol = Role::firstOrCreate(['slug' => Role::ADMIN], ['nombre' => 'Administrador General']);

        return User::factory()->create(['role_id' => $rol->id]);
    }

    public function test_deleting_an_active_user_only_deactivates_it(): void
    {
        $admin = $this->admin();
        $usuario = User::factory()->create(['role_id' => $admin->role_id]);

        $this->actingAs($admin)
            ->delete(route('admin.usuarios.destroy', $usuario))
            ->assertRedirect(route('admin.usuarios.index'));

        $usuario->refresh();
        $this->assertFalse((bool) $usuario->activo);
        $this->assertNotNull($usuario->desactivado_at);
        $this->assertNull($usuario->reactivado_at);
        $this->assertDatabaseCount('usuarios_bajas', 0);
    }

    public function test_deleting_an_inactive_user_removes_it_and_records_the_permanent_exit(): void
    {
        $admin = $this->admin();
        $usuario = User::factory()->create([
            'role_id' => $admin->role_id,
            'activo' => false,
            'desactivado_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.usuarios.destroy', $usuario))
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseMissing('users', ['id' => $usuario->id]);
        $this->assertDatabaseHas('usuarios_bajas', [
            'user_id' => $usuario->id,
            'email' => $usuario->email,
            'rol_nombre' => 'Administrador General',
            'eliminado_por' => $admin->name,
        ]);

        $baja = UsuarioBaja::firstOrFail();
        $this->assertNotNull($baja->ingreso_at);
        $this->assertNotNull($baja->desactivado_at);
        $this->assertNotNull($baja->eliminado_at);
    }

    public function test_a_user_who_never_accepted_the_terms_is_deleted_right_away_without_a_record(): void
    {
        $admin = $this->admin();
        $usuario = User::factory()->create([
            'role_id' => $admin->role_id,
            'terminos_aceptados_at' => null,
            'credenciales_pendientes' => 'ClaveTemporal123',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.usuarios.destroy', $usuario))
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseMissing('users', ['id' => $usuario->id]);
        $this->assertDatabaseCount('usuarios_bajas', 0);
    }

    public function test_reactivating_saves_the_return_date_and_deactivating_again_clears_it(): void
    {
        $admin = $this->admin();
        $usuario = User::factory()->create(['role_id' => $admin->role_id, 'activo' => false]);

        $this->actingAs($admin)
            ->post(route('admin.usuarios.reactivar', $usuario))
            ->assertRedirect();

        $usuario->refresh();
        $this->assertTrue((bool) $usuario->activo);
        $this->assertNotNull($usuario->reactivado_at);

        // Como al cambiar el estado desde el formulario de edición.
        $usuario->update(['activo' => false]);
        $usuario->refresh();

        $this->assertNotNull($usuario->desactivado_at);
        $this->assertNull($usuario->reactivado_at);
    }

    public function test_dates_list_only_contains_the_dates_that_exist(): void
    {
        $sinNada = new User();
        $this->assertSame([], $sinNada->fechasParaMostrar());

        $completo = new User();
        $completo->terminos_aceptados_at = now()->subDays(3);
        $completo->desactivado_at = now()->subDays(2);
        $completo->reactivado_at = now()->subDay();

        $this->assertCount(3, $completo->fechasParaMostrar());

        $soloIngreso = new User();
        $soloIngreso->terminos_aceptados_at = now();

        $this->assertCount(1, $soloIngreso->fechasParaMostrar());
    }

    public function test_the_permanent_removals_page_requires_login(): void
    {
        $this->get(route('admin.usuarios.bajas'))->assertRedirect(route('login'));
    }
}
