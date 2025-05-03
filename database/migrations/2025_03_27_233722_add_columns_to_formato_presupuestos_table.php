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
        Schema::table('formato_presupuestos', function (Blueprint $table) {
            if (!Schema::hasColumn('formato_presupuestos', 'nombre')) {
                $table->string('nombre')->nullable()->after('id');
            }
            if (!Schema::hasColumn('formato_presupuestos', 'descripcion')) {
                $table->string('descripcion')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('formato_presupuestos', 'imagen_principal')) {
                $table->string('imagen_principal')->nullable()->after('descripcion');
            }
            if (!Schema::hasColumn('formato_presupuestos', 'contenido_texto')) {
                $table->text('contenido_texto')->nullable()->after('imagen_principal');
            }
            if (!Schema::hasColumn('formato_presupuestos', 'opciones_personalizables')) {
                $table->json('opciones_personalizables')->nullable()->after('contenido_texto');
            }
            if (!Schema::hasColumn('formato_presupuestos', 'activo')) {
                $table->boolean('activo')->default(true)->after('opciones_personalizables');
            }
            if (!Schema::hasColumn('formato_presupuestos', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formato_presupuestos', function (Blueprint $table) {
            if (Schema::hasColumn('formato_presupuestos', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('formato_presupuestos', 'activo')) {
                $table->dropColumn('activo');
            }
            if (Schema::hasColumn('formato_presupuestos', 'opciones_personalizables')) {
                $table->dropColumn('opciones_personalizables');
            }
            if (Schema::hasColumn('formato_presupuestos', 'contenido_texto')) {
                $table->dropColumn('contenido_texto');
            }
            if (Schema::hasColumn('formato_presupuestos', 'imagen_principal')) {
                $table->dropColumn('imagen_principal');
            }
            if (Schema::hasColumn('formato_presupuestos', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
            if (Schema::hasColumn('formato_presupuestos', 'nombre')) {
                $table->dropColumn('nombre');
            }
        });
    }
};
