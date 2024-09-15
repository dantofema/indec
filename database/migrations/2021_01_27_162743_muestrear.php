<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Muestrear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/segmentacion-core/muestreo/muestrear.sql';
        DB::unprepared(file_get_contents($path));

        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/describe_despues_de_muestreo.sql';
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
