<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('epp', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('cantidad_total')->default(0);
            $table->unsignedInteger('stock_disponible')->default(0);
            $table->timestamps();
        });

        Schema::create('entrega_epp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('epp_id')->constrained('epp')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_entrega');
            $table->enum('estado_elemento', ['bueno', 'regular', 'deteriorado'])->default('bueno');
            $table->date('fecha_devolucion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('herramienta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['disponible', 'prestada', 'en_mantenimiento', 'baja'])->default('disponible');
            $table->timestamps();
        });

        Schema::create('entrega_herramienta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('herramienta_id')->constrained('herramienta')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_entrega');
            $table->enum('estado_herramienta', ['bueno', 'regular', 'daniado'])->default('bueno');
            $table->date('fecha_devolucion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entrega_herramienta');
        Schema::dropIfExists('herramienta');
        Schema::dropIfExists('entrega_epp');
        Schema::dropIfExists('epp');
    }
};
