<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_nominas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Trabajador que recibe el pago
            $table->unsignedBigInteger('registrado_por'); // Admin que registra el pago
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->string('tipo_pago', 50); // jornal, quincenal, etc.
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_nominas');
    }
};
