<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class GenerarDescripcionDeSegmentosTextual extends Migration
{
    /**
     * Run the migrations.
     * https://github.com/manureta/segmenter/issues/13
     * @return void
     */
    public function up()
    {
        //
        Eloquent::unguard();
        //$this->command->info('- Instalando función para generar descripción de segmentos textual...');
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/descripcion_segmentos.sql';
        DB::unprepared(file_get_contents($path));
        //$this->command->info('instalada!');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Eloquent::unguard();
        DB::statement('drop function if exists indec.descripcion_segmentos(aglomerado text)');
    }
}

