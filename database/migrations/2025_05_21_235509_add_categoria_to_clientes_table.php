<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->enum('categoria', [
                'Pedir requisitos por llamada',
                'Crear presupuesto',
                'Presupuesto creado',
                'Presupuesto Presentado',
                'Primer follow up',
                'Segundo follow up',
                'Tercer follow up',
                'Follow up correo',
                'Eventos pospuestos'
            ])->default('Pedir requisitos por llamada');
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
