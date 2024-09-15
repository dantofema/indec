<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Domicilio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //id, prov, nom_provincia, ups, nro_area, dpto, nom_dpto, codaglo, codloc, nom_loc, codent, nom_ent, frac, radio, mza, lado, nro_inicial, nro_final, orden_recorrido_viv, nro_listado, ccalle, ncalle, nro_catastral, nrocatastralredef, piso, pisoredef, casa, dpto_habitacion, sector, edificio, entrada, cod_tipo_viv, cod_tipo_vivredef, cod_subt_vivloc, descripcion, descripcion_lado, cod_no_enc, fec_no_enc, motivo_no_enc, fec_alta_viv, fec_ult_mod, cod_postal, orden_recorrido_mza, estado, esta_supervisado, creadoen, chequeadoen, editadoen, borradoen, creado, chequeado, editado, borrado, actualizador, supervisor, usuario, tipo_base, tipo_tarea
	Schema::create ( 'domicilios', function ($table) {
		$table->integer ( 'id' );
		$table->integer ( 'listado_id');
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
		$table->string ( 'editado' );
		$table->string ( 'borrado' );
		$table->string ( 'actualizador' );
		$table->string ( 'supervisor' );
		$table->string ( 'usuario' );
		$table->string ( 'tipo_base' );
		$table->string ( 'tipo_tarea' );
	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
     DB::unprepared('DROP SEQUENCE if exists domicilios_id_seq cascade;');
	 Schema::dropIfExists('domicilios');
    }
}
