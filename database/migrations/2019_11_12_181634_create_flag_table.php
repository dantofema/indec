<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name')->unique();
            $table->string('file_original_name');
            $table->boolean('imported');
            $table->integer('rows_imported');
            $table->integer('total_rows');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flag');
    }
}
