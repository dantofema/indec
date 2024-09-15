<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcesadoATablaArchivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
      try{
        Schema::table('archivos', function (Blueprint $table) {
            $table->boolean('procesado')->add();
            $table->string('tabla')->nullable()->add();
            $table->string('epsg_def')->nullable()->add();
        });
      }catch (Exception $e){
        if($e->getCode()=='42701'){
          echo __('Ya existe la columna
');
          }else{
          echo ($e);
        }
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
    }
}
