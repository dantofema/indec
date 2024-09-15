<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CargarTopologiaFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/indec.cargarTopology.function.sql';
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
        $query="DROP FUNCTION IF EXISTS indec.cargartopologia(character varying,character varying);";
        Eloquent::unguard();
        DB::statement($query);
    }
}
