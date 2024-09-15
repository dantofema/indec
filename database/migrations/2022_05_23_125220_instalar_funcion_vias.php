<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstalarFuncionVias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/function.indec.vias.sql';
        DB::unprepared(file_get_contents($path));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $query="DROP FUNCTION IF EXISTS indec.vias();";
        Eloquent::unguard();
        DB::statement($query);
    }
}
