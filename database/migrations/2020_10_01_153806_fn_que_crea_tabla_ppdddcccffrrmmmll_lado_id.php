<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FnQueCreaTablaPpdddcccffrrmmmllLadoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // fix bug diferente nombres de campos
        $path = 'app/developer_docs/segmentacion-core/lados_completos/cargar_lados.sql';
        DB::unprepared(file_get_contents($path));
        // crea tabla con ids integer de lados ppdddcccffrrmmmll
        $path = 'app/developer_docs/segmentacion-core/segmentos_lados_desde_hasta.sql';
        DB::unprepared(file_get_contents($path));
        // crea tabla para seguir un segemto desde hasta y por los lados que pasa

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
