<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('nit', 30)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();
            $table->text('producto_servicio')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
        });

        Schema::create('compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedor')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('total', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compra')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumo')->onDelete('cascade');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_compra');
        Schema::dropIfExists('compra');
        Schema::dropIfExists('proveedor');
    }
};
