<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domicilio extends Model
{
    const CREATED_AT = null; //'creation_date';
    const UPDATED_AT = null; //'last_update';
    protected $table= 'domicilios';
//    public $fillable = [id, prov, nom_provincia, ups, nro_area, dpto, nom_dpto, codaglo, codloc, nom_loc, codent, nom_ent, frac, radio, mza, lado, nro_inicial, nro_final, orden_recorrido_viv, nro_listado, ccalle, ncalle, nro_catastral, nrocatastralredef, piso, pisoredef, casa, dpto_habitacion, sector, edificio, entrada, cod_tipo_viv, cod_tipo_vivredef, cod_subt_vivloc, descripcion, descripcion_lado, cod_no_enc, fec_no_enc, motivo_no_enc, fec_alta_viv, fec_ult_mod, cod_postal, orden_recorrido_mza, estado, esta_supervisado, creadoen, chequeadoen, editadoen, borradoen, creado, chequeado, editado, borrado, actualizador, supervisor, usuario, tipo_base, tipo_tarea, segmento];
	public $fillable = ['prov','listado_id','nom_provin','dpto','nom_dpto','codaglo','codloc','nom_loc','codent','nom_ent','frac','radio','mza','lado',
'nro_inicia','nro_final','orden_reco','nrolist','ccalle','ncalle','nrocatastr','piso','casa','dptohab','sector','edificio','entrada','tipoviv',
'descrip','descripl','cpostal','ordrecmza','fechrele','tiptarea','segmento'];


	static public function cargar_csv($file){
         $fileD = fopen($file,"r");
         $column=fgetcsv($fileD,0,"|");
         while(!feof($fileD)){
	  $rowData=[];
          $rowData=fgetcsv($fileD,0,"|");
          $inserted_data=[];
	  if (is_array ($rowData)){
              foreach ($rowData as $key => $value_feature) {
		
/*
              foreach ($value_feature as $value){
                    $inserted_data[]=$value;
              } 
*/
    //         $listado[]= Listado::create($inserted_data);
	    	    $inserted_data[]=$value_feature;
	     }          
		$item_listado = new Listado();
		$i=0;
		foreach ($column as $col){
			$col=strtolower($col);
			$item_listado->$col = $inserted_data[$i];
			$i++;
		}
	        $listado[]=$item_listado;
	  }
         }
         return $listado;
	}

    public function listado()
    {
    	return $this->belongsTo('App\Listado');
    }

    public function show(Domicilio $domicilio)
    {
        return $domicilio;
    }

}
