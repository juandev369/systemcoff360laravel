<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\PagoNomina;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagoNominaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the roles
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_can_access_nomina_index()
    {
        // Admin user (role_id = 1)
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->get(route('nomina.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.nomina.index');
        $response->assertViewHasAll(['totalTrabajadores', 'totalPagado', 'pagadoMes', 'totalPagos', 'trabajadores', 'pagos']);
    }

    public function test_admin_can_store_nomina_payment()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $trabajador = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->post(route('nomina.store'), [
            'id_trabajador' => $trabajador->id,
            'tipo_pago' => 'jornal',
            'monto' => 150.50,
            'fecha_pago' => '2026-08-14',
            'descripcion' => 'Pago semanal de prueba',
        ]);

        $response->assertRedirect(route('nomina.index'));
        $response->assertSessionHas('alert');

        $this->assertDatabaseHas('pago_nominas', [
            'user_id' => $trabajador->id,
            'registrado_por' => $admin->id,
            'monto' => 150.50,
            'tipo_pago' => 'jornal',
            'descripcion' => 'Pago semanal de prueba',
        ]);
    }

    public function test_admin_can_delete_nomina_payment()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $trabajador = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        $pago = PagoNomina::create([
            'user_id' => $trabajador->id,
            'registrado_por' => $admin->id,
            'fecha' => '2026-08-14',
            'monto' => 200.00,
            'tipo_pago' => 'mensual',
            'descripcion' => 'A pagar',
        ]);

        $response = $this->actingAs($admin)->delete(route('nomina.destroy', $pago->id));

        $response->assertRedirect(route('nomina.index'));
        $response->assertSessionHas('alert');

        $this->assertDatabaseMissing('pago_nominas', [
            'id' => $pago->id,
        ]);
    }
}
