<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RadioLocalidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         $sql = file_get_contents(app_path() . '/developer_docs/radio_localidad.up.sql');
      try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
	        echo __('Omitiendo creación de tabla de relación radio localidad...
		      ');
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
//        Schema::dropIfExists('radio_localidad');
    }
}
