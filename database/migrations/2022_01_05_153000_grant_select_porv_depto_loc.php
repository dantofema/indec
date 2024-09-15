<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GrantSelectPorvDeptoLoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       try{
            echo DB::statement("GRANT USAGE ON SCHEMA public TO geoestadistica;");
            echo DB::statement("grant select on table public.provincia to geoestadistica;");
            echo DB::statement("grant select on table public.departamentos to geoestadistica;");
            echo DB::statement("grant select on table public.localidad to geoestadistica;");
            echo __('Se dieron permisos de lectura a tabla provincias,departamentos, localidades a geoestadistica
');
        }catch(Illuminate\Database\QueryException $e){
            echo __('No se pudo dar permisos de lectura a tabla corrida a geoestadistica_admin
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
