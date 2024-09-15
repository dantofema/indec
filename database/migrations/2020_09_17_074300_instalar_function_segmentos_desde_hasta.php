<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InstalarFunctionSegmentosDesdeHasta extends Migration
{
    /**
     * Run the migrations.
     * https://github.com/hernan-alperin/segmentacion-core/issues/11
     * @return void
     */
    public function up()
    {
        //
        Eloquent::unguard();
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/segmentos_desde_hasta.sql';
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
        Eloquent::unguard();
        DB::statement('drop function if exists indec.segmentos_desde_hasta(d record)');
    }
}


