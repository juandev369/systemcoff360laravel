<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria_activo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('activo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categoria_activo')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('codigo', 50)->nullable()->unique();
            $table->enum('estado', ['operativo', 'en_mantenimiento', 'daniado', 'de_baja'])->default('operativo');
            $table->decimal('valor_comercial', 12, 2)->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('mantenimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->constrained('activo')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['preventivo', 'correctivo'])->default('preventivo');
            $table->date('fecha');
            $table->decimal('costo', 10, 2)->default(0);
            $table->text('descripcion')->nullable();
            $table->date('proximo_mantenimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimiento');
        Schema::dropIfExists('activo');
        Schema::dropIfExists('categoria_activo');
    }
};
