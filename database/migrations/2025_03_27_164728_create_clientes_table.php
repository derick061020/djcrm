<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('contacto');
            $table->string('tipo_evento');
            $table->dateTime('fecha_estimada');
            $table->json('requerimientos')->nullable();
            $table->json('horas_trabajo')->nullable();
            $table->text('ubicacion_local');
            $table->integer('cantidad_personas');
            $table->json('referencias_musicales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
