<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WorkerDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        $this->seed(RoleSeeder::class);
    }

    public function test_worker_can_access_dashboard_and_see_metrics()
    {
        // 1. Create a worker user
        $worker = User::factory()->create([
            'role_id' => 2, // Trabajador
            'estado' => 'activo',
        ]);

        // 2. Create tasks with different statuses assigned to this worker
        $adminId = User::factory()->create(['role_id' => 1])->id;

        // Pending task
        $pendingTaskId = DB::table('tarea')->insertGetId([
            'nombre' => 'Deshierbe',
            'descripcion' => 'Deshierbar lote norte',
            'estado' => 'pendiente',
            'prioridad' => 'alta',
            'creada_por' => $adminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('asignacion_tarea')->insert([
            'tarea_id' => $pendingTaskId,
            'user_id' => $worker->id,
            'fecha_asignacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // In progress task
        $progressTaskId = DB::table('tarea')->insertGetId([
            'nombre' => 'Abonado',
            'descripcion' => 'Aplicar abono',
            'estado' => 'en_progreso',
            'prioridad' => 'media',
            'creada_por' => $adminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('asignacion_tarea')->insert([
            'tarea_id' => $progressTaskId,
            'user_id' => $worker->id,
            'fecha_asignacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Completed task
        $completedTaskId = DB::table('tarea')->insertGetId([
            'nombre' => 'Poda',
            'descripcion' => 'Podar árboles',
            'estado' => 'completada',
            'prioridad' => 'baja',
            'creada_por' => $adminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('asignacion_tarea')->insert([
            'tarea_id' => $completedTaskId,
            'user_id' => $worker->id,
            'fecha_asignacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create a payroll record for the worker
        DB::table('pago_nominas')->insert([
            'user_id' => $worker->id,
            'registrado_por' => $adminId,
            'fecha' => now()->format('Y-m-d'),
            'monto' => 150000,
            'tipo_pago' => 'quincenal',
            'descripcion' => 'Pago quincena de agosto',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Act as the worker and request the dashboard
        $response = $this->actingAs($worker)->get(route('trabajador.dashboard'));

        // 5. Assertions
        $response->assertStatus(200);
        $response->assertViewIs('trabajador.dashboard');
        
        // Assert metrics count in view variables
        $response->assertViewHas('totalTareas', 3);
        $response->assertViewHas('tareasPendientes', 1);
        $response->assertViewHas('tareasProceso', 1);
        $response->assertViewHas('tareasFinalizadas', 1);
        
        // Assert notification lists contain payroll & task notifications
        $response->assertViewHas('totalNotif', 2); // 1 task notification (pending task) + 1 payroll notification

        // Check if welcome message and username is visible
        $response->assertSee('Bienvenido, ' . $worker->name);
    }

    public function test_dashboard_redirects_worker_to_worker_dashboard()
    {
        $worker = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($worker)->get('/dashboard');

        $response->assertRedirect(route('trabajador.dashboard'));
    }

    public function test_dashboard_redirects_admin_to_admin_dashboard()
    {
        $admin = User::factory()->create([
            'role_id' => 1,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }
}
