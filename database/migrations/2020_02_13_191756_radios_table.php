<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RadiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SI ya no esta la tabla de radios.
        if (! Schema::hasTable('radio')){
        	 $sql = file_get_contents(app_path() . '/developer_docs/radio.up.sql');
        	 DB::unprepared($sql);
        }else{
             echo 'No se crea tabla de radios xq ya se encuentra una.
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
        //Schema::dropIfExists('radio');
    }
}
