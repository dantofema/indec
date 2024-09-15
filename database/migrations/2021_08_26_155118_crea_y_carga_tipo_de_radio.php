<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaYCargaTipoDeRadio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SI ya no esta la tabla de subtipo_viviendas.
        if (! Schema::hasTable('tipo_de_radio')){
           $sql = file_get_contents(app_path() .
                         '/developer_docs/tipo_de_radio.up.sql');
           DB::unprepared($sql);
        }else{
             echo 'No se crea tabla de tipo_de_radio xq ya se encuentra una.
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
//        Schema::dropIfExists('tipo_de_radio');        //
    }
}
