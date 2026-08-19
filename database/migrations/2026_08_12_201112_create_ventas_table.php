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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cosecha_id'); // Relación con la cosecha
            $table->date('fecha');
            $table->decimal('cantidad_kg', 10, 2);
            $table->decimal('precio_kg', 10, 2);
            $table->decimal('total', 12, 2); // Lo calcularemos antes de guardar
            $table->string('comprador', 150);
            $table->string('tipo_cafe', 50);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
