<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDomiciliosTableMoreNullables extends Migration
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
                $table->string('fechrele')->nullable()->change();;
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
