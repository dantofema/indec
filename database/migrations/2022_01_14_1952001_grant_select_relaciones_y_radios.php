<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GrantSelectRelacionesYRadios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       try{
            echo DB::statement("grant select on table public.localidad_departamento to geoestadistica;");
            echo DB::statement("grant select on table public.radio to geoestadistica;");
            echo DB::statement("grant select on table public.fraccion to geoestadistica;");
            echo DB::statement("grant select on table public.aglomerados to geoestadistica;");
            echo DB::statement("grant select on table public.radio_localidad to geoestadistica;");
            echo DB::statement("grant select on table public.tipo_de_radio to geoestadistica;");
            echo __('Se dieron permisos de lectura a tabla Radio, Localidad-Departamento, Radio-Localidad, Aglomerado, Tipo de Radio y Fraccion a geoestadistica
');
        }catch(Illuminate\Database\QueryException $e){
            echo __('No se pudo dar permisos de lectura a tablas Radio, Localidad_Departaimento, Radio-Localidad, Aglomerado, Tipo de Radio y Fraccion a geoestadistica
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
