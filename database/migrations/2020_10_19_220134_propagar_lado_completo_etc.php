<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PropagarLadoCompletoEtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // crea vista con los segi, segd de segmentacion de lado completo
        $path = 'app/developer_docs/segmentacion-core/lados_completos/v_segmentos_lados_completos.sql';
        DB::unprepared(file_get_contents($path));
        // crea fns para propagar segmentado por lad completo a tabla segmentaciones 
        $path = 'app/developer_docs/segmentacion-core/lados_completos/lados_completos_a_tabla_segmentacion.sql';
        DB::unprepared(file_get_contents($path));
        // crea fn para segmentar listado 
        $path = 'app/developer_docs/segmentacion-core/manzanas_independientes/segmentar_listado_equilibrado.sql';
        DB::unprepared(file_get_contents($path));
        // crea fn para juntar segmentos de 0 viviendas 
        $path = 'app/developer_docs/segmentacion-core/juntar_segmentos.sql';
        DB::unprepared(file_get_contents($path));
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
