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
        Schema::dropIfExists('cliente_formato_presupuesto');
        Schema::dropIfExists('item_presupuestos');
        Schema::dropIfExists('presupuestos');
        Schema::dropIfExists('formato_presupuestos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('formato_presupuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->text('contenido_texto')->nullable();
            $table->json('opciones_personalizables')->nullable();
            $table->boolean('activo')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('item_presupuestos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('cliente_formato_presupuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('formato_presupuesto_id')->constrained()->onDelete('cascade');
            $table->text('datos_personalizados')->nullable();
            $table->timestamps();
        });
    }
};
