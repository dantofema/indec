<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DescribeSegmentosConDireccionesPisosLetras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/describe_sin_muestreo_ffrr.sql';
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
