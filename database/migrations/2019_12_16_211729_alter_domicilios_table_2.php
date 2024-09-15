<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDomiciliosTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasTable('domicilios')) {
    //
	Schema::table('domicilios', function (Blueprint $table) {
        	$table->renameColumn('nomdpto', 'nom_dpto');
	        $table->renameColumn('nomloc', 'nom_loc');
        	$table->renameColumn('noment', 'nom_ent');
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
