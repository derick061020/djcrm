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
            $table->foreignId('aprobado_por_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->timestamp('aprobado_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['aprobado_por_id']);
            $table->dropColumn(['aprobado_por_id', 'aprobado_at']);
        });
    }
};
