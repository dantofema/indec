<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('entidad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
*/
    $sql = file_get_contents(app_path() . '/developer_docs/entidad.up.sql');
    try{
       DB::unprepared($sql);
    }catch(Illuminate\Database\QueryException $e){
       DB::Rollback();
       echo __('Error creando entidad...');
    }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entidad');
    }
}
