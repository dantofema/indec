<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReInstalarFuncionesAdyacenciasCoreBugfixFfrr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Eloquent::unguard();
        DB::statement('CREATE SCHEMA IF NOT EXISTS indec');
        //$this->command->info('- Instalando funcion de adyacencias...');
        $path = 'app/developer_docs/segmentacion-core/lados_completos/generar_adyacencias.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('Generar Adyacencias instalado!');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
