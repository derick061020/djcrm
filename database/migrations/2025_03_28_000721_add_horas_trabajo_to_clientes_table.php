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
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'hora_inicio')) {
                $table->time('hora_inicio')->nullable()->after('fecha_estimada');
            }
            if (!Schema::hasColumn('clientes', 'hora_fin')) {
                $table->time('hora_fin')->nullable()->after('hora_inicio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'hora_fin')) {
                $table->dropColumn('hora_fin');
            }
            if (Schema::hasColumn('clientes', 'hora_inicio')) {
                $table->dropColumn('hora_inicio');
            }
        });
    }
};
