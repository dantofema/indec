<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     /**
        Schema::create('provincia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

        });
     **/
   	 $sql = file_get_contents(app_path() . '/developer_docs/localidad.up.sql');
      try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
	        echo __('Omitiendo creación de tabla de localidad...
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
//        Schema::dropIfExists('localidad');
    }
}
