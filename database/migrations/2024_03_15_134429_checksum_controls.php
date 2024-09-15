<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('checksum_controls')) {
            Schema::create('checksum_controls', function (Blueprint $table) {
                $table->foreignId('archivo_id')->constrained();
                $table->string('checksum');
                $table->timestamps();
            });
        } else {
            echo __("Ya existe la tabla checksum_controls. No se creará");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checksum_controls');
    }
};
