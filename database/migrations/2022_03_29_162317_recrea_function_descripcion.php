<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RecreaFunctionDescripcion extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        //
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


