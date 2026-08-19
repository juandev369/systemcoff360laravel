<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the roles
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_can_access_inventario_index()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->get(route('inventario.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.inventario.index');
        $response->assertViewHasAll(['insumos', 'herramientas', 'epps', 'trabajadores', 'totales', 'tiposInsumos', 'estadosHerramientas']);
    }

    public function test_admin_can_store_insumo()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->post(route('inventario.insumo.store'), [
            'nombre' => 'Sulfato de Zinc',
            'tipo' => 'fertilizante',
            'unidad' => 'kg',
            'stock_actual' => 50,
            'stock_minimo' => 10,
            'precio_unidad' => 4500,
            'ubicacion_bodega' => 'Estante B-2',
        ]);

        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('alert');

        $this->assertDatabaseHas('insumo', [
            'nombre' => 'Sulfato de Zinc',
            'tipo' => 'fertilizante',
            'unidad' => 'kg',
            'stock_actual' => 50,
            'stock_minimo' => 10,
            'precio_unidad' => 4500,
            'ubicacion_bodega' => 'Estante B-2',
        ]);
    }

    public function test_admin_can_store_herramienta()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->post(route('inventario.herramienta.store'), [
            'nombre' => 'Podadora de Altura',
            'descripcion' => 'Gasolina 2 tiempos',
            'estado' => 'disponible',
        ]);

        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('alert');

        $this->assertDatabaseHas('herramienta', [
            'nombre' => 'Podadora de Altura',
            'descripcion' => 'Gasolina 2 tiempos',
            'estado' => 'disponible',
        ]);
    }

    public function test_admin_can_store_epp()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->post(route('inventario.epp.store'), [
            'nombre' => 'Gafas protectoras de policarbonato',
            'descripcion' => 'Anti-empañamiento',
            'cantidad_total' => 15,
            'stock_disponible' => 12,
        ]);

        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('alert');

        $this->assertDatabaseHas('epp', [
            'nombre' => 'Gafas protectoras de policarbonato',
            'descripcion' => 'Anti-empañamiento',
            'cantidad_total' => 15,
            'stock_disponible' => 12,
        ]);
    }
}
