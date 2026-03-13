<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_lineas_accion_ejes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('numero_eje')->unsigned()->unique()->comment('Número oficial del eje: 1, 2, 3, 4');
            $table->string('eje')->comment('Nombre completo del eje');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_lineas_accion_ejes');
    }
};
