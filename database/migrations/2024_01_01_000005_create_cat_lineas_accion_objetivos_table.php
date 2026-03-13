<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_lineas_accion_objetivos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('numero_objetivo')->unsigned()->unique()->comment('Número oficial del objetivo específico: 1-10');
            $table->string('objetivo')->comment('Descripción del objetivo específico');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_lineas_accion_objetivos');
    }
};
