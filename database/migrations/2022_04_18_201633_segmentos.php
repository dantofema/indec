<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Segmentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        If (! Schema::hasTable('segmentos')){
         Schema::create('segmentos', function (Blueprint $table) {
            $table->bigIncrements('id')->index();
            $table->string('prov')->index();
            $table->string('nom_prov')->nullable();
            $table->string('dpto')->index();
            $table->string('nom_dpto');
            $table->string('codent')->nullable()->index();
            $table->string('nom_ent')->nullable();
            $table->string('codloc');
            $table->string('nom_loc');
            $table->string('frac');
            $table->string('radio');
            $table->string('tipo');
            $table->string('seg')->nullable();
            $table->string('vivs');
            $table->timestamps();
         });
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
        If ( Schema::dropIfExists('segmentos')) {
          echo "Se dropeo segmentos";
        }
    }
}
