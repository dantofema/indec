<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JuntarSegmentosFFRRConMenosDeMismoLado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Recrea fn para juntar segmentos de 0 viviendas ffrr
        // y crea funcion para juntar segmentos con menos de N viviendas 
        $path = 'app/developer_docs/segmentacion-core/juntar_segmentos.sql';
        DB::unprepared(file_get_contents($path));

        // También se recrea tipo_de_radio
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/tipo_de_radio.sql';
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
