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
            $table->integer('overall_satisfaction')->nullable()->comment('Satisfacción general (1-5 estrellas)');
            $table->integer('service_quality')->nullable()->comment('Calidad del servicio (1-5 estrellas)');
            $table->integer('product_quality')->nullable()->comment('Calidad del producto (1-5 estrellas)');
            $table->text('survey_comments')->nullable()->comment('Comentarios de la encuesta');
            $table->boolean('would_recommend')->nullable()->comment('¿Recomendaría nuestros servicios?');
            $table->timestamp('survey_completed_at')->nullable()->comment('Fecha de finalización de la encuesta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn([
                'overall_satisfaction',
                'service_quality',
                'product_quality',
                'survey_comments',
                'would_recommend',
                'survey_completed_at'
            ]);
        });
    }
};
