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
            $table->boolean('contract_accepted')->default(false)->after('updated_at');
            $table->boolean('alternative_requested')->default(false)->after('contract_accepted');
            $table->timestamp('contract_accepted_at')->nullable()->after('contract_accepted');
            $table->timestamp('alternative_requested_at')->nullable()->after('alternative_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['contract_accepted', 'alternative_requested', 'contract_accepted_at', 'alternative_requested_at']);
        });
    }
};
