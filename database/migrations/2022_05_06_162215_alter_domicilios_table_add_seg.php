<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDomiciliosTableAddSeg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	if ( Schema::hasTable('domicilios')) {
	   Schema::table('domicilios', function (Blueprint $table) {
	    $table->string('segmento')->nullable()->add();
	    $table->string('cpostal')->nullable()->change();
	    $table->string('ordrecmza')->nullable()->change();
	    $table->string('tiptarea')->nullable()->change();
	   });
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
	      if ( Schema::hasTable('domicilios')) {
	          Schema::table('domicilios', function (Blueprint $table) {
	            $table->dropColumn(['segmento']);
	          });
        }
    }
}
