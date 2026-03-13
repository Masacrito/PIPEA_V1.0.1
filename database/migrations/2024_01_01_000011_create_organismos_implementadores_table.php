<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organismos_implementadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ambito')->nullable();
            $table->unsignedBigInteger('id_nivel')->nullable();
            $table->unsignedBigInteger('id_poder')->nullable();
            $table->string('nombre');
            $table->string('siglas')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->foreign('id_ambito')->references('id')->on('cat_ambitos')->nullOnDelete();
            $table->foreign('id_nivel')->references('id')->on('cat_niveles')->nullOnDelete();
            $table->foreign('id_poder')->references('id')->on('cat_poderes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organismos_implementadores');
    }
};
