<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lineas_accion', function (Blueprint $table) {
            $table->id();

            // Clasificación estratégica PI-PEA
            $table->unsignedBigInteger('id_eje')->nullable();
            $table->unsignedBigInteger('id_objetivo')->nullable();
            $table->unsignedBigInteger('id_prioridad')->nullable();
            $table->unsignedBigInteger('id_estrategia')->nullable();
            $table->unsignedBigInteger('id_plazo')->nullable();
            $table->unsignedBigInteger('id_frecuencia')->nullable();

            // Responsabilidad institucional
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_organismo'); // CORRECCIÓN 1: agregado directo

            // Medición — CORRECCIÓN 3: meta como decimal
            $table->decimal('meta', 15, 4)->nullable();
            $table->string('unidad_medida')->nullable();
            $table->decimal('avance_cuantitativo', 15, 4)->default(0);

            // Control
            $table->text('comentarios')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Claves foráneas - catálogos estratégicos
            $table->foreign('id_eje')->references('id')->on('cat_lineas_accion_ejes')->nullOnDelete();
            $table->foreign('id_objetivo')->references('id')->on('cat_lineas_accion_objetivos')->nullOnDelete();
            $table->foreign('id_prioridad')->references('id')->on('cat_lineas_accion_prioridades')->nullOnDelete();
            $table->foreign('id_estrategia')->references('id')->on('cat_lineas_accion_estrategias')->nullOnDelete();
            $table->foreign('id_plazo')->references('id')->on('cat_lineas_accion_plazos')->nullOnDelete();
            $table->foreign('id_frecuencia')->references('id')->on('cat_lineas_accion_frec_medicion')->nullOnDelete();

            // Claves foráneas - responsabilidad
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->foreign('id_organismo')->references('id')->on('organismos_implementadores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas_accion');
    }
};
