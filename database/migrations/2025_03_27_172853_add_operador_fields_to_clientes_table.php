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
            $table->boolean('estado')->default(false)->after('referencias_musicales');
            $table->string('formato_evento')->nullable()->after('estado');
            $table->text('observaciones_operador')->nullable()->after('formato_evento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('estado');
            $table->dropColumn('formato_evento');
            $table->dropColumn('observaciones_operador');
        });
    }
};
