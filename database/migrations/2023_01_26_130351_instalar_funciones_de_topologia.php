<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstalarFuncionesDeTopologia extends Migration
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
        $path = 'app/developer_docs/function.indec.cargarTopologiaM.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('Generar Adyacencias instalado!');
        $path = 'app/developer_docs/function.indec.crossTopologia.sql';
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
        $query="DROP FUNCTION IF EXISTS indec.cargartopologia(character varying, character varying, integer);";
        Eloquent::unguard();
        DB::statement($query);
        $query="DROP FUNCTION IF EXISTS indec.crosstopologia(character varying, character varying);";
        DB::statement($query);

    }
}
