<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cosecha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->foreignId('registrada_por')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('cantidad_kg', 10, 2);
            $table->enum('calidad', ['primera', 'segunda', 'tercera', 'pasilla'])->default('primera');
            $table->decimal('humedad_pct', 5, 2)->nullable();
            $table->enum('tipo_cafe', ['cereza', 'pergamino_seco', 'verde_almendra', 'mojado_lavado'])->default('cereza');
            $table->enum('estado', ['almacenada', 'vendida', 'en_beneficio', 'descartada'])->default('almacenada');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cosecha_id')->nullable()->constrained('cosecha')->nullOnDelete();
            $table->foreignId('registrada_por')->constrained('users')->onDelete('cascade');
            $table->string('comprador', 150);
            $table->date('fecha');
            $table->decimal('peso_kg', 10, 2);
            $table->decimal('precio_kg', 10, 2);
            $table->decimal('total', 12, 2);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta');
        Schema::dropIfExists('cosecha');
    }
};
