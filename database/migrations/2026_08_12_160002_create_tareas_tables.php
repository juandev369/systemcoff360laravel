<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->nullable()->constrained('lotes')->nullOnDelete();
            $table->foreignId('creada_por')->constrained('users')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->enum('prioridad', ['alta', 'media', 'baja'])->default('media');
            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'cancelada'])->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->date('fecha_completada')->nullable();
            $table->timestamps();
        });

        Schema::create('asignacion_tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tarea')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->decimal('horas_dedicadas', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('evidencia_tarea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tarea')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('archivo', 300);
            $table->enum('tipo', ['foto', 'video', 'audio', 'documento'])->default('foto');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidencia_tarea');
        Schema::dropIfExists('asignacion_tarea');
        Schema::dropIfExists('tarea');
    }
};
