<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstalarFuncionesSegmentacionCore extends Migration
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
        //$this->command->info('- Instalando funcion de conteo...');
        $path = 'app/developer_docs/segmentacion-core/lados_completos/cargar_conteos.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('Generar Conteos instalado!');
        //$this->command->info('- Instalando funcion de adyacencias...');
        $path = 'app/developer_docs/segmentacion-core/lados_completos/generar_adyacencias.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('Generar Adyacencias instalado!');
        //$this->command->info('- Instalando segmentador a mananas independientes (listado)...');
        $path = 'app/developer_docs/segmentacion-core/manzanas_independientes/segmentar_equilibrado.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('Segmantación de manzanas independientes instalado!');
        echo 'segmentacion-core instalado';

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Eloquent::unguard();
        DB::statement('DROP SCHEMA IF EXISTS indec CASCADE');
    }
}
