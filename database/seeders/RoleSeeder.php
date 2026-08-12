<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Acceso total al sistema']
        );

        Role::firstOrCreate(
            ['nombre' => 'Trabajador'],
            ['descripcion' => 'Acceso limitado a tareas asignadas']
        );
    }
}
