<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_nomina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('registrado_por')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->enum('tipo_pago', ['jornal', 'quincena', 'mensual', 'anticipo', 'bono'])->default('jornal');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('certificacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('entidad_certif', 150);
            $table->date('fecha_expedicion');
            $table->date('fecha_vencimiento')->nullable();
            $table->string('documento', 300)->nullable();
            $table->enum('estado', ['vigente', 'vencida', 'en_tramite'])->default('vigente');
            $table->timestamps();
        });

        Schema::create('registro_clima', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->unique();
            $table->decimal('lluvia_mm', 6, 2)->default(0);
            $table->decimal('temp_min', 5, 2)->nullable();
            $table->decimal('temp_max', 5, 2)->nullable();
            $table->decimal('humedad_pct', 5, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_clima');
        Schema::dropIfExists('certificacion');
        Schema::dropIfExists('pago_nomina');
    }
};
