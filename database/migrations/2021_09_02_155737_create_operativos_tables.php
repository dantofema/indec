<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperativosTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('operativos_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
*/
       $sql = file_get_contents(app_path() . '/developer_docs/operativos.up.sql');
       try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
          echo __('Error creando tablas operativos ...').$e;
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operativo');
        Schema::dropIfExists('operativo__localidad');
        Schema::dropIfExists('operativo__paraje');
        Schema::dropIfExists('operativo__base_antartica');
        Schema::dropIfExists('operativo__entidad');
        Schema::dropIfExists('operativo__gobierno_local');
    }
}
