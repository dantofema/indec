<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexProvincias extends Migration
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
          Schema::table('provincias', function(Blueprint $table)
          {
              $table->index('id');
          });
        } catch(Illuminate\Database\QueryException $e){
            if ($e->getCode()=='42P07'){
                echo "Ya existe el indice \n";
            }
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
