<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo', 120);
            $table->text('mensaje');
            $table->enum('tipo', ['tarea', 'sistema', 'alerta', 'clima', 'mantenimiento'])->default('sistema');
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacion');
    }
};
