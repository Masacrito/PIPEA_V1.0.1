<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_avance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_linea_accion');
            $table->unsignedBigInteger('id_usuario');

            // Snapshot del nombre de usuario al momento del registro
            $table->string('usuario')->nullable();

            // Evidencia
            $table->string('medio_verificacion')->nullable();
            $table->string('documento')->nullable();
            $table->string('url')->nullable();
            $table->text('comentario')->nullable();

            // CORRECCIÓN 2: era text, ahora datetime
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();

            $table->foreign('id_linea_accion')->references('id')->on('lineas_accion')->cascadeOnDelete();
            $table->foreign('id_usuario')->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_avance');
    }
};
