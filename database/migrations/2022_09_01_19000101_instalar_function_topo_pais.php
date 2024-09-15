<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstalarFunctionTopoPais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/function.indec.cargarTopologiaPais.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'app/developer_docs/function.indec.radios.sql';
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
        $query="DROP FUNCTION IF EXISTS indec.cargar_topologia_pais();";
        Eloquent::unguard();
        DB::statement($query);
        $query="DROP FUNCTION IF EXISTS indec.radios();";
        Eloquent::unguard();
        DB::statement($query);
    }
}
