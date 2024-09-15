<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalidadDepartamentosTable extends Migration
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
       DB::beginTransaction();
       $sql = file_get_contents(app_path() . '/developer_docs/localidad_departamento.up.sql');
       try{
         DB::unprepared($sql);
         DB::commit();
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
	        echo __('Omitiendo creación de tabla de relación localidad con departamentos...
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
//        Schema::dropIfExists('localidad_departamento');
    }
}
