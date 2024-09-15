<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GaussKruggerBAenDB extends Migration
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
        DB::unprepared('INSERT into spatial_ref_sys (srid, auth_name,
        auth_srid, proj4text, srtext) values ( 98333, \'sr-org\', 8333,
        \'+proj=tmerc +lat_0=-34.6297166 +lon_0=-58.4627 +k=1 +x_0=100000
        +y_0=100000 +ellps=intl +units=m +no_defs \', \'PROJCS["Gauss Krugger
        BA",GEOGCS["GCS_Campo_Inchauspe",DATUM["D_Campo_Inchauspe",SPHEROID["International_1924",6378388.0,297.0]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]],PROJECTION["Transverse_Mercator"],PARAMETER["False_Easting",100000.0],PARAMETER["False_Northing",100000.0],PARAMETER["Central_Meridian",-58.4627],PARAMETER["Scale_Factor",1.0],PARAMETER["Latitude_Of_Origin",-34.6297166],UNIT["Meter",1.0]]\');');
        }catch(Exception $e){
            if ($e->getCode()==23505){
//                $this->command->info('Proyeccion CABA ya instalada!');
                echo 'Proyeccion CABA ya instalada. Duplicado.
';
                return 0;
            }else{
              if ($e->getCode()=='42P01'){
                                echo 'Proyeccion CABA NO Se pudo instalar. Falta Postgis?
                ';
                        }
                else {
                   dd($e);
                }
            }
        }

	echo 'Proyeccion CABA instalada. SRID:8333
';
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
