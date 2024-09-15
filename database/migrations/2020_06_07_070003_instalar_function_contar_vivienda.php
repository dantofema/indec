<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InstalarFunctionContarVivienda extends Migration
{
    /**
     * Run the migrations.
     * https://github.com/hernan-alperin/segmentacion-core/issues/7
     * https://github.com/manureta/segmenter/issues/27
     * @return void
     */
    public function up()
    {
        //
        Eloquent::unguard();
        //$this->command->info('- Instalando función para discernir qué vivienda contar...');
        $path = 'app/developer_docs/segmentacion-core/contar_vivienda.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('instalada!');
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
        DB::statement('drop function if exists indec.contar_vivienda(aglomerado text) cascade');
    }
}


