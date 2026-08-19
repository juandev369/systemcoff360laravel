<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EntregaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the roles
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_can_access_entregas_index()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->get(route('entregas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.entregas.index');
        $response->assertViewHasAll(['trabajadores', 'herramientas', 'epps', 'entregasHerramientas', 'entregasEpp']);
    }

    public function test_admin_can_deliver_and_return_herramienta()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $trabajador = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        // Insert a tool in available state
        $herramientaId = DB::table('herramienta')->insertGetId([
            'nombre' => 'Motosierra STIHL',
            'descripcion' => 'Motosierra de gasolina',
            'estado' => 'disponible',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Deliver the tool
        $response = $this->actingAs($admin)->post(route('entregas.herramienta.store'), [
            'id_herramienta' => $herramientaId,
            'id_usuario' => $trabajador->id,
            'fecha_entrega' => now()->format('Y-m-d'),
            'estado_herramienta' => 'bueno',
            'observaciones' => 'Entregado para el lote principal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert');

        // Check tool state updated to 'prestada'
        $this->assertDatabaseHas('herramienta', [
            'id' => $herramientaId,
            'estado' => 'prestada',
        ]);

        // Check delivery record created
        $this->assertDatabaseHas('entrega_herramientas', [
            'herramienta_id' => $herramientaId,
            'user_id' => $trabajador->id,
            'fecha_devolucion' => null,
        ]);

        $entrega = DB::table('entrega_herramientas')->where('herramienta_id', $herramientaId)->first();

        // Return the tool
        $responseReturn = $this->actingAs($admin)->patch(route('entregas.herramienta.return', $entrega->id));
        
        $responseReturn->assertRedirect();
        $responseReturn->assertSessionHas('alert');

        // Check tool state reverted to 'disponible'
        $this->assertDatabaseHas('herramienta', [
            'id' => $herramientaId,
            'estado' => 'disponible',
        ]);

        // Check devolution date is set
        $this->assertDatabaseMissing('entrega_herramientas', [
            'id' => $entrega->id,
            'fecha_devolucion' => null,
        ]);
    }

    public function test_admin_can_deliver_and_return_epp()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $trabajador = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        // Insert EPP
        $eppId = DB::table('epp')->insertGetId([
            'nombre' => 'Casco de seguridad',
            'descripcion' => 'Casco de protección',
            'cantidad_total' => 10,
            'stock_disponible' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Deliver EPP
        $response = $this->actingAs($admin)->post(route('entregas.epp.store'), [
            'id_epp' => $eppId,
            'id_usuario' => $trabajador->id,
            'fecha_entrega' => now()->format('Y-m-d'),
            'estado_elemento' => 'bueno',
            'observaciones' => 'Casco de obra estándar',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert');

        // Check EPP stock decreased
        $this->assertDatabaseHas('epp', [
            'id' => $eppId,
            'stock_disponible' => 9,
        ]);

        // Check EPP delivery record created
        $this->assertDatabaseHas('entrega_epps', [
            'epp_id' => $eppId,
            'user_id' => $trabajador->id,
            'fecha_devolucion' => null,
        ]);

        $entrega = DB::table('entrega_epps')->where('epp_id', $eppId)->first();

        // Return EPP
        $responseReturn = $this->actingAs($admin)->patch(route('entregas.epp.return', $entrega->id));
        
        $responseReturn->assertRedirect();
        $responseReturn->assertSessionHas('alert');

        // Check EPP stock increased back to original
        $this->assertDatabaseHas('epp', [
            'id' => $eppId,
            'stock_disponible' => 10,
        ]);

        // Check devolution date is set
        $this->assertDatabaseMissing('entrega_epps', [
            'id' => $entrega->id,
            'fecha_devolucion' => null,
        ]);
    }
}
