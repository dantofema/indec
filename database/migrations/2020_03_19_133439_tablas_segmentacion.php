<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TablasSegmentacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	 DB::statement('CREATE SCHEMA IF NOT EXISTS segmentacion');
	 $sql = file_get_contents(app_path() . '/developer_docs/schema_segmentacion.up.sql');
	 DB::unprepared($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('segmentacion.conteos');
        Schema::dropIfExists('segmentacion.adyacencias');
	DB::statement('DROP SCHEMA IF EXISTS segmentacion CASCADE');
    }
}
