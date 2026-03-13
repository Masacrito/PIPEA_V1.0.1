<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prioridad_estrategia', function (Blueprint $table) {
            $table->unsignedBigInteger('id_prioridad');
            $table->unsignedBigInteger('id_estrategia');
            $table->primary(['id_prioridad', 'id_estrategia']);

            $table->foreign('id_prioridad')->references('id')->on('cat_lineas_accion_prioridades')->cascadeOnDelete();
            $table->foreign('id_estrategia')->references('id')->on('cat_lineas_accion_estrategias')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prioridad_estrategia');
    }
};
