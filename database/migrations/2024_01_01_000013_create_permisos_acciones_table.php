<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos_acciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_accion');
            $table->primary(['id_rol', 'id_accion']);

            $table->foreign('id_rol')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('id_accion')->references('id')->on('acciones_menu')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos_acciones');
    }
};
