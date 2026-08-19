<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WorkerNominaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        $this->seed(RoleSeeder::class);
    }

    public function test_worker_can_view_payroll_index_and_metrics()
    {
        $worker = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        $adminId = User::factory()->create(['role_id' => 1])->id;

        // Insert payments for this worker across different months
        DB::table('pago_nominas')->insert([
            [
                'user_id' => $worker->id,
                'registrado_por' => $adminId,
                'fecha' => '2026-08-10',
                'monto' => 120000.00,
                'tipo_pago' => 'jornal',
                'descripcion' => 'Jornal semana 1 agosto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $worker->id,
                'registrado_por' => $adminId,
                'fecha' => '2026-08-17',
                'monto' => 130000.00,
                'tipo_pago' => 'jornal',
                'descripcion' => 'Jornal semana 2 agosto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $worker->id,
                'registrado_por' => $adminId,
                'fecha' => '2026-07-20',
                'monto' => 250000.00,
                'tipo_pago' => 'quincenal',
                'descripcion' => 'Quincena julio',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Access the payroll index
        $response = $this->actingAs($worker)->get(route('trabajador.nomina'));

        $response->assertStatus(200);
        $response->assertViewIs('trabajador.nomina.index');

        // Verify metrics computed correctly:
        // Total Received: 120k + 130k + 250k = 500k
        $response->assertViewHas('totalRecibido', 500000);
        
        // Recibido este mes (assuming test runs in August 2026 as per local time 2026-08-19):
        // Month of the test: 2026-08-19 => 2026-08 (120k + 130k = 250k)
        $response->assertViewHas('recibidoMes', 250000);
        
        // Total Pagos: 3
        $response->assertViewHas('totalPagos', 3);
        
        // Último Pago: '2026-08-17'
        $response->assertViewHas('ultimoPago', '2026-08-17');

        // Check if group counts are loaded correctly
        $porMes = $response->viewData('porMes');
        $this->assertCount(2, $porMes); // August and July

        // Check that payroll rows are rendered
        $response->assertSee('Jornal semana 1 agosto');
        $response->assertSee('Jornal semana 2 agosto');
        $response->assertSee('Quincena julio');
    }
}
