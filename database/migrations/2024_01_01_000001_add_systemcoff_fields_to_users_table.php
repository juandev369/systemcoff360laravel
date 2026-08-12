<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega a la tabla `users` (Breeze) los campos del modelo original `usuario`:
     * id_rol, DNI, telefono, estado, intentos_fallidos.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            $table->string('dni', 20)->nullable()->unique()->after('name');
            $table->string('telefono', 20)->nullable()->after('email');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('password');
            $table->unsignedTinyInteger('intentos_fallidos')->default(0)->after('estado');
            $table->timestamp('bloqueado_hasta')->nullable()->after('intentos_fallidos');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['dni', 'telefono', 'estado', 'intentos_fallidos', 'bloqueado_hasta']);
        });
    }
};
