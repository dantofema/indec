<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileViewerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('file_viewer')) {
            Schema::create('file_viewer', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained();
                $table->foreignId('archivo_id')->constrained();
                $table->timestamps();
            });
        } else {
            echo __("Ya existe la tabla file_viewer. No se creará");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_viewer');
    }
}
