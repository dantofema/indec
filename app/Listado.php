<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Domicilio;

class Listado extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';
    protected $table= 'lista';
//    public $fillable = [id, prov, nom_provincia, ups, nro_area, dpto, nom_dpto, codaglo, codloc, nom_loc, codent, nom_ent, frac, radio, mza, lado, nro_inicial, nro_final, orden_recorrido_viv, nro_listado, ccalle, ncalle, nro_catastral, nrocatastralredef, piso, pisoredef, casa, dpto_habitacion, sector, edificio, entrada, cod_tipo_viv, cod_tipo_vivredef, cod_subt_vivloc, descripcion, descripcion_lado, cod_no_enc, fec_no_enc, motivo_no_enc, fec_alta_viv, fec_ult_mod, cod_postal, orden_recorrido_mza, estado, esta_supervisado, creadoen, chequeadoen, editadoen, borradoen, creado, chequeado, editado, borrado, actualizador, supervisor, usuario, tipo_base, tipo_tarea];

	static public function cargar_csv($file){
         $fileD = fopen($file,"r");
         $column=fgetcsv($fileD,0,",");
	 $count=0;
	  flash('Iniciando lectura de Domicilios...');
         while(!feof($fileD)){
	  $count++;
	  $rowData=[];
          $rowData=fgetcsv($fileD,0,",");
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
		$item_listado = new Domicilio();
		$i=0;
		foreach ($column as $col){
			$col=strtolower($col);
			$item_listado->$col = $inserted_data[$i];
			$i++;
		}
		$item_listado->save();
	        $listado[]=$item_listado;

	  }
	  flash('Leido: '.$count.' registros');
         }
         return $listado;
	}

    /**
     * Get the comments for the blog post.
     */
    public function domicilios()
    {
        return $this->hasMany('App\Domicilio');
    }
}
