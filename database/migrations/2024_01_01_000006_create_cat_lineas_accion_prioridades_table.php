<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_lineas_accion_prioridades', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('numero')->unsigned()->unique()->comment('Número oficial de la prioridad: 1-67');
            $table->text('prioridad')->comment('Descripción completa de la prioridad');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_lineas_accion_prioridades');
    }
};
