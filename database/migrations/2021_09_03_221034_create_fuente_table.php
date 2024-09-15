<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*        Schema::create('fuente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
*/
       $sql = file_get_contents(app_path() . '/developer_docs/fuente.up.sql');
       try{
           DB::unprepared($sql);
       }catch(Illuminate\Database\QueryException $e){
          DB::Rollback();
          echo __('Error creando tablas fuente ...').$e;
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuente');
    }
}
