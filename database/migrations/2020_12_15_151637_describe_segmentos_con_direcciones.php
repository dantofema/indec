<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DescribeSegmentosConDirecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/describe_segmentos_con_direcciones.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/describe_segmentos_con_direcciones_ffrr.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/describe_despues_de_muestreo_ffrr.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/describe_sin_muestreo_ffrr.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/excluye_colectivas.sql';
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
