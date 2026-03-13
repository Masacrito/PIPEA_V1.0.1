<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acciones_menu', function (Blueprint $table) {
            $table->id();
            $table->string('controlador');
            $table->string('accion');
            $table->string('menu')->nullable();
            $table->integer('orden')->default(0);
            $table->string('icono')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acciones_menu');
    }
};
