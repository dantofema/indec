<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnasATablaArchivos extends Migration
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
          $table->bigInteger('user_id')->index()->add();
            $table->string('nombre_original')->add();
            $table->string('nombre')->add();
            $table->string('tipo')->index()->add();
            $table->string('checksum')->add();
            $table->string('size')->add();
            $table->string('mime')->add();
        });
      }catch (Exception $e){
        if($e->getCode()=='42701'){
          echo __('Ya existen la columnas
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
