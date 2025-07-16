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
        Schema::create('satisfaction_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clientes')->onDelete('cascade');
            $table->integer('overall_satisfaction')->nullable(); // 1-5 estrellas
            $table->integer('service_quality')->nullable(); // 1-5 estrellas
            $table->integer('product_quality')->nullable(); // 1-5 estrellas
            $table->text('comments')->nullable();
            $table->boolean('would_recommend')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satisfaction_surveys');
    }
};
