<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGobiernoLocalDepartamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('gobierno_local_departamento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
*/
       $sql = file_get_contents(app_path() . '/developer_docs/gobierno_local_departamento.up.sql');
       try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
          echo __('Error creando relacion gobierno_local - departamento...');
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gobierno_local_departamento');
    }
}
