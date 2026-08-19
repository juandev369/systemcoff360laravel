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
        Schema::create('entrega_herramientas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('herramienta_id');
            $table->unsignedBigInteger('user_id'); // Trabajador
            $table->date('fecha_entrega');
            $table->date('fecha_devolucion')->nullable();
            $table->string('estado_herramienta', 50)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_herramientas');
    }
};
