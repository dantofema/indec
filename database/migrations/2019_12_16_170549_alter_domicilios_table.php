<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDomiciliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	if ( Schema::hasTable('domicilios')) {
    //
Schema::table('domicilios', function (Blueprint $table) {
	$table->dropColumn('piso'); 
	$table->dropColumn('nro_catastral'); 
	$table->dropColumn('estado'); 
	$table->dropColumn('esta_supervisado'); 
	$table->dropColumn('creadoen'); 
	$table->dropColumn('chequeadoen'); 
	$table->dropColumn('editadoen'); 
	$table->dropColumn('borradoen'); 
	$table->dropColumn('creado'); 
	$table->dropColumn('chequeado'); 
	$table->dropColumn('editado'); 
	$table->dropColumn('borrado'); 
	$table->dropColumn('actualizador'); 
	$table->dropColumn('supervisor'); 
	$table->dropColumn('usuario'); 
	$table->dropColumn('tipo_base'); 
	$table->dropColumn('cod_subt_vivloc'); 
	$table->dropColumn('cod_tipo_viv'); 
	$table->dropColumn('cod_no_enc'); 
	$table->dropColumn('fec_no_enc'); 
	$table->dropColumn('motivo_no_enc'); 
	$table->dropColumn('fec_ult_mod'); 

	$table->renameColumn('nom_provincia', 'nom_provin');
	$table->renameColumn('nom_dpto', 'nomdpto');
	$table->renameColumn('nom_loc', 'nomloc');
	$table->renameColumn('nom_ent', 'noment');
	$table->renameColumn('nro_inicial', 'nro_inicia');
	$table->renameColumn('orden_recorrido_viv', 'orden_reco');
	$table->renameColumn('nro_listado', 'nrolist');
	$table->renameColumn('nrocatastralredef', 'nrocatastr');
	$table->renameColumn('pisoredef', 'piso');
	$table->renameColumn('dpto_habitacion', 'dptohab');
	$table->renameColumn('cod_tipo_vivredef', 'tipoviv');
	$table->renameColumn('descripcion', 'descrip');
	$table->renameColumn('descripcion_lado', 'descripl');
	$table->renameColumn('cod_postal', 'cpostal');
	$table->renameColumn('orden_recorrido_mza', 'ordrecmza');
	$table->renameColumn('fec_alta_viv', 'fechrele');
	$table->renameColumn('tipo_tarea', 'tiptarea');
});		
	}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
         Schema::dropIfExists('domicilios');

    }
}
