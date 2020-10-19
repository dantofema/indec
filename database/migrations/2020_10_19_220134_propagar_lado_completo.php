<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PropagarLadoCompleto extends Migration
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
        // crea fn para propagar segmentado por lad completo a tabla segmentaciones 
        $path = 'app/developer_docs/segmentacion-core/lados_completos/lados_completos_a_tabla_segmentacion.sql';
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
