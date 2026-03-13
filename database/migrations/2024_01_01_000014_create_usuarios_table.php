<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_organismo');
            $table->string('nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();

            $table->foreign('id_rol')->references('id')->on('roles');
            $table->foreign('id_organismo')->references('id')->on('organismos_implementadores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
