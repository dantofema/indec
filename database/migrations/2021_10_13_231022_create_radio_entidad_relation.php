<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadioEntidadRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $sql = file_get_contents(app_path() . '/developer_docs/radio_entidad.up.sql');
       try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
	  echo __('Error creando relacion radio - entidad...
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
        Schema::dropIfExists('radio_entidad');
    }
}
