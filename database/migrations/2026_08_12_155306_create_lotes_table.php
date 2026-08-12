<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->string('tipo_plantacion', 80);
            $table->text('ubicacion')->nullable();
            $table->decimal('area_hectareas', 8, 2)->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};