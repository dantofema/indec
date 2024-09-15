<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinciaTableOperativo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     /**

     **/
/*
	 $sql = file_get_contents(database_path() . '/migrations/2020_01_02_202716_create_provincia_table.up.sql');
	 DB::unprepared($sql);
*/
 // Agrego primary key en tabla operativo
 If (Schema::hasTable('operativo')){
    Schema::table('operativo', function (Blueprint $table) {
        $table->primary('id')->change();
    });

}

 If (! Schema::hasTable('operativo_provincia')){
    Schema::create('operativo_provincia', function (Blueprint $table) {
        $table->increments('id');
        $table->BigInteger('provincia_id')->index();
        $table->BigInteger('operativo_id')->index();
        $table->timestamps();

        $table->foreign('provincia_id')->references('id')->on('provincia');
        $table->foreign('operativo_id')->references('id')->on('operativo');

    });
   }else{
	  echo __('Omitiendo creación de tabla de Operativo-Provincia existente...
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
        Schema::dropIfExists('operativo_provincia');
    }
}
