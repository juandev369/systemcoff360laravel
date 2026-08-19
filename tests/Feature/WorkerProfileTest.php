<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WorkerProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_worker_can_view_profile_edit_page()
    {
        $worker = User::factory()->create([
            'role_id' => 2,
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($worker)->get(route('trabajador.perfil'));

        $response->assertStatus(200);
        $response->assertViewIs('trabajador.perfil.edit');
        $response->assertViewHas('usuario', $worker);
    }

    public function test_worker_can_update_profile_information()
    {
        $worker = User::factory()->create([
            'role_id' => 2,
            'name' => 'Juan Ramirez',
            'dni' => '1234567',
            'email' => 'juan@coff.com',
            'telefono' => '3001234567',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($worker)->patch(route('trabajador.perfil.update'), [
            'nombres' => 'Juan David',
            'apellidos' => 'Ramirez Lizcano',
            'dni' => '7654321',
            'email' => 'juandavid@coff.com',
            'telefono' => '3229876543',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert');

        $worker->refresh();
        $this->assertEquals('Juan David Ramirez Lizcano', $worker->name);
        $this->assertEquals('7654321', $worker->dni);
        $this->assertEquals('juandavid@coff.com', $worker->email);
        $this->assertEquals('3229876543', $worker->telefono);
    }

    public function test_worker_cannot_update_profile_with_existing_email_or_dni()
    {
        $anotherUser = User::factory()->create([
            'role_id' => 2,
            'dni' => '9999999',
            'email' => 'another@coff.com',
        ]);

        $worker = User::factory()->create([
            'role_id' => 2,
            'name' => 'Juan Ramirez',
            'dni' => '1234567',
            'email' => 'juan@coff.com',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($worker)->patch(route('trabajador.perfil.update'), [
            'nombres' => 'Juan',
            'apellidos' => 'Ramirez',
            'dni' => '9999999', // Already taken
            'email' => 'another@coff.com', // Already taken
        ]);

        $response->assertSessionHasErrors(['dni', 'email']);
    }

    public function test_worker_can_update_password()
    {
        $worker = User::factory()->create([
            'role_id' => 2,
            'password' => Hash::make('old-password'),
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($worker)->put(route('trabajador.password.update'), [
            'current_password' => 'old-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert');

        $worker->refresh();
        $this->assertTrue(Hash::check('new-secure-password', $worker->password));
    }

    public function test_worker_cannot_update_password_with_incorrect_current_password()
    {
        $worker = User::factory()->create([
            'role_id' => 2,
            'password' => Hash::make('old-password'),
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($worker)->put(route('trabajador.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertFalse(Hash::check('new-secure-password', $worker->password));
    }
}
