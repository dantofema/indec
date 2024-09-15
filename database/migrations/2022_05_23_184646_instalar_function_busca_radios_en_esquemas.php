<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstalarFunctionBuscaRadiosEnEsquemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $path = 'app/developer_docs/function.indec.busca_radios_en_esquemas.sql';
        DB::unprepared(file_get_contents($path));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $query="DROP FUNCTION IF EXISTS indec.busca_radio_en_esquema_listado(codigo text);";
        Eloquent::unguard();
        DB::statement($query);
        $query="DROP FUNCTION IF EXISTS indec.radios_de_listados();";
        Eloquent::unguard();
        DB::statement($query);
        $query="DROP FUNCTION IF EXISTS indec.radios_de_arcs();";
        Eloquent::unguard();
        DB::statement($query);
    }
}
