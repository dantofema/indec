<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalidadGobiernoLocalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('localidad_gobierno_local', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
*/
       $sql = file_get_contents(app_path() . '/developer_docs/localidad_gobierno_local.up.sql');
       try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
          echo __('Error creando relacion localidad - gobierno_local ...');
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localidad_gobierno_local');
    }
}
