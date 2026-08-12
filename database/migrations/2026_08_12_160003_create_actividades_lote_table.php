<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_lote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo_actividad', [
                'siembra', 'poda', 'fertilizacion', 'fumigacion',
                'deshierbe', 'riego', 'recoleccion', 'mantenimiento', 'analisis_suelo'
            ]);
            $table->date('fecha');
            $table->text('descripcion')->nullable();
            $table->decimal('costo', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_lote');
    }
};
