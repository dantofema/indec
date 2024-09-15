<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->bigIncrements('id');
		$table->string ( 'prov' );
		$table->string ( 'nom_provincia' );
		$table->string ( 'ups' );
		$table->string ( 'nro_area' );
		$table->string ( 'dpto' );
		$table->string ( 'nom_dpto' );
		$table->string ( 'codaglo' );
		$table->string ( 'codloc' );
		$table->string ( 'nom_loc' );
		$table->string ( 'codent' );
		$table->string ( 'nom_ent' );
		$table->string ( 'frac' );
		$table->string ( 'radio' );
		$table->string ( 'mza' );
		$table->string ( 'lado' );
		$table->string ( 'nro_inicial' );
		$table->string ( 'nro_final' );
		$table->string ( 'orden_recorrido_viv' );
		$table->string ( 'nro_listado' );
		$table->string ( 'ccalle' );
		$table->string ( 'ncalle' );
		$table->string ( 'nro_catastral' );
		$table->string ( 'nrocatastralredef' );
		$table->string ( 'piso' );
		$table->string ( 'pisoredef' );
		$table->string ( 'casa' );
		$table->string ( 'dpto_habitacion' );
		$table->string ( 'sector' );
		$table->string ( 'edificio' );
		$table->string ( 'entrada' );
		$table->string ( 'cod_tipo_viv' );
		$table->string ( 'cod_tipo_vivredef' );
		$table->string ( 'cod_subt_vivloc' );
		$table->string ( 'descripcion' );
		$table->string ( 'descripcion_lado' );
		$table->string ( 'cod_no_enc' );
		$table->string ( 'fec_no_enc' );
		$table->string ( 'motivo_no_enc' );
		$table->string ( 'fec_alta_viv' );
		$table->string ( 'fec_ult_mod' );
		$table->string ( 'cod_postal' );
		$table->string ( 'orden_recorrido_mza' );
		$table->string ( 'estado' );
		$table->string ( 'esta_supervisado' );
		$table->string ( 'creadoen' );
		$table->string ( 'chequeadoen' );
		$table->string ( 'editadoen' );
		$table->string ( 'borradoen' );
		$table->string ( 'creado' );
		$table->string ( 'chequeado' );
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
        Schema::dropIfExists('data');
    }
}
