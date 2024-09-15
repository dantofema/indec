<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GrantSelectCorrida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       try{
            DB::statement("grant select on table public.corrida to geoestadistica_admin;");
            echo __('Se dieron permisos de lectura a tabla corrida a geoestadistica_admin
');
        }catch(Illuminate\Database\QueryException $e){
            echo __('No se pudo dar permisos de lectura a tabla corrida a geoestadistica_admin
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
    }
}
