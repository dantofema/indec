<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstalarFunctionManzanas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $path = 'app/developer_docs/function.indec.manzanas.sql';
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
        $query="DROP FUNCTION IF EXISTS indec.manzanas();";
        Eloquent::unguard();
        DB::statement($query);
    }
}
