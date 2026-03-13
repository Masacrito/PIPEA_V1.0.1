<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_lineas_accion_frec_medicion', function (Blueprint $table) {
            $table->id();
            $table->string('frecuencia')->unique()->comment('Ej. Mensual, Trimestral, Semestral, Anual');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_lineas_accion_frec_medicion');
    }
};
