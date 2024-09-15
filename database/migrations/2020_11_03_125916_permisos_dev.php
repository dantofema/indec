<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PermisosDev extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //        dd($this->getConnection());
        $database = env('DB_DATABASE', 'postgres') ;
        Eloquent::unguard();
        try{
            $query="GRANT ALL ON DATABASE $database TO mretamozo;";
            DB::statement($query);
            echo __('Permisos para usuario mretamozo...
');
            }
        catch(Illuminate\Database\QueryException $e){
            DB::Rollback();
            echo __('Error con usuario mretamozo...
');
            }
 
        try{
            Eloquent::unguard();
            $query="GRANT ALL ON DATABASE $database TO manuel;";
            DB::statement($query);
            echo __('Permisos para usuario manuel...
');
            }
        catch(Exception $e){
            echo __('Error con usuario manuel...
');
            DB::Rollback();
            }

        try{
            Eloquent::unguard();
            $query="GRANT ALL ON DATABASE $database TO halperin;";
            DB::statement($query);
            }
        catch(Exception $e){
            echo __('Error con usuario halperin...
');
            DB::Rollback();
            }


        try{
            Eloquent::unguard();
            $query="GRANT ALL ON DATABASE $database TO vhere;";
            DB::statement($query);
            }
        catch(Exception $e){
            echo __('Error con usuario vheredia...
');
            DB::Rollback();
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
