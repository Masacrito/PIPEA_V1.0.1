<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Quién lo hizo
            $table->unsignedBigInteger('id_usuario')->nullable(); // null si es acción de sistema
            $table->string('usuario_nombre')->nullable();         // snapshot del nombre

            // Qué pasó
            $table->string('evento');           // created | updated | deleted
            $table->string('modelo');           // Nombre de la clase del modelo
            $table->string('tabla');            // Nombre de la tabla afectada
            $table->unsignedBigInteger('registro_id'); // ID del registro afectado

            // Valores anteriores vs nuevos (solo en updated)
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();

            // Contexto adicional
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index(['modelo', 'registro_id']);
            $table->index('id_usuario');
            $table->index('evento');
            $table->index('fecha_registro');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
