<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GrantSelectSubtipoviv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       try{
            echo DB::statement("grant select on table public.subtipo_vivienda to geoestadistica;");
            echo __('Se dieron permisos de lectura a tabla Subtipo de vivienda a geoestadistica
');
        }catch(Illuminate\Database\QueryException $e){
            echo __('No se pudo dar permisos de lectura a tablas Subtipo de vivienda a geoestadistica
'.$e->getMessage());
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
