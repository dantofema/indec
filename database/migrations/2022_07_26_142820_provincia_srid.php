<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProvinciaSrid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( Schema::hasTable('provincia')) {
            Schema::table('provincia', function (Blueprint $table) {
                $table->integer('srid')->nullable()->add();
           });
        } 
        $sql = file_get_contents(app_path() . '/developer_docs/provincias_srid.sql');
        // esto lo hace el seeeder tambien. pero hay que hacerlo 2 veces 
        // porque en el caso de una instalación desde cero van primero los migrate y luego los seed
        // y en ese caso provincia todavía no tiene codigo
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ( Schema::hasTable('provincia')) {
            Schema::table('provincia', function (Blueprint $table) {
                $table->dropColumn('srid');
            });
        }
    }
}
