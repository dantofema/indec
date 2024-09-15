<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SegmentarEquilibradoFfrr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Borro primero si existe por un tema de cambio de nombre de parametro
        // no permitido
        $query = 'DROP FUNCTION if exists
        indec.segmentar_equilibrado_ffrr(text,integer,integer,integer);';
        Eloquent::unguard();
        DB::statement($query);

        //
        $path = 'app/developer_docs/segmentacion-core/manzanas_independientes/segmentar_equilibrado_ffrr.sql';
        DB::unprepared(file_get_contents($path));        //
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
