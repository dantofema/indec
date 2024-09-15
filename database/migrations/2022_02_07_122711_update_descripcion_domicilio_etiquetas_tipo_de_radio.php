<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDescripcionDomicilioEtiquetasTipoDeRadio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // bugfix subtipo de viviendas
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/descripcion_domicilio.sql';
        DB::unprepared(file_get_contents($path));
        // bugfix etiquetas mixto mas de 10 segmentos en 1 localidad
        $path = 'app/developer_docs/segmentacion-core/descripcion_segmentos/tipo_de_radio.sql';
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
    }
}
