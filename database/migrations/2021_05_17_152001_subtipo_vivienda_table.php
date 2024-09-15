<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubtipoViviendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SI ya no esta la tabla de subtipo_viviendas.
        if (! Schema::hasTable('subtipo_vivienda')){
        	 $sql = file_get_contents(app_path() .
                         '/developer_docs/subtipo_vivienda.up.sql');
        	 DB::unprepared($sql);
        }else{
             echo 'No se crea tabla de subtipo_viviendas xq ya se encuentra una.
';
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('subtipo_vivienda');
    }
}
