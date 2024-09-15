<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDomiciliosTableNullables extends Migration
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
	    $table->string('ups')->nullable()->change();;
	    $table->string('nro_area')->nullable()->change();;
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
    }
}
