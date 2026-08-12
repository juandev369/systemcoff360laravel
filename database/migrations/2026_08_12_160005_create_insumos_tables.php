<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->enum('tipo', ['fertilizante', 'pesticida', 'herbicida', 'fungicida', 'abono_organico', 'enmienda', 'otro']);
            $table->enum('unidad', ['kg', 'L', 'bulto', 'g', 'ml', 'unidad'])->default('kg');
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->decimal('precio_unidad', 10, 2)->default(0);
            $table->string('ubicacion_bodega', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('aplicacion_insumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumo')->onDelete('cascade');
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('cantidad_aplicada', 10, 2);
            $table->enum('metodo', ['foliar', 'al_suelo', 'fertirriego', 'drench'])->default('foliar');
            $table->decimal('dosis_ha', 8, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicacion_insumo');
        Schema::dropIfExists('insumo');
    }
};
