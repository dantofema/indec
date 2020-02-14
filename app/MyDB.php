<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MyDB extends Model
{
    //a
	public static function createSchema($esquema)
	{
	 DB::statement('CREATE SCHEMA IF NOT EXISTS e'.$esquema);
	}

	public static function moverDBF($file_name,$esquema)
	{
         $tabla = strtolower( substr($file_name,strrpos($file_name,'/')+1,-4) );
         $esquema = 'e'.$esquema;
             DB::beginTransaction();
             DB::unprepared('ALTER TABLE '.$tabla.' SET SCHEMA '.$esquema);
             DB::unprepared('DROP TABLE IF EXISTS '.$esquema.'.listado CASCADE');
             DB::unprepared('ALTER TABLE '.$esquema.'.'.$tabla.' RENAME TO listado');
             DB::unprepared('ALTER TABLE '.$esquema.'.listado ADD COLUMN id serial');
             if (! Schema::hasColumn($esquema.'.listado' , 'tipoviv')){
                 if (Schema::hasColumn($esquema.'.listado' , 'cod_tipo_v')){
                     DB::unprepared('ALTER TABLE '.$esquema.'.listado RENAME cod_tipo_v TO tipoviv');
                 }elseif (Schema::hasColumn($esquema.'.listado' , 'cod_viv')){
                            DB::unprepared('ALTER TABLE '.$esquema.'.listado RENAME cod_viv TO tipoviv');
                     }else{
                         DB::statement('ALTER TABLE '.$esquema.'.listado ADD COLUMN tipoviv text;');
                     }
             }
             DB::unprepared("Select indec.cargar_conteos('".$esquema."')");
             DB::unprepared("Select indec.generar_adyacencias('".$esquema."')");
             DB::commit();
	}

    
	public static function agregarsegisegd($esquema)
	{
	 DB::statement('ALTER TABLE e'.$esquema.'.arc ADD COLUMN segi integer;');
	 DB::statement('ALTER TABLE e'.$esquema.'.arc ADD COLUMN segd integer;');
	}

	public static function segmentar_equilibrado($esquema,$deseado = 10)
	{
    	return DB::statement("SELECT indec.segmentar_equilibrado('e".$esquema."',".$deseado.");");

     // SQL retrun: Select segmento_id,count(*) FROM e0777.segmentacion GROUP BY segmento_id;
	}
	
    public static function segmentar_equilibrado_ver($esquema)
	{
        $esquema = 'e'.$esquema;
    	return DB::select('
                        SELECT segmento_id,count(*) vivs,count(distinct mza) as mzas,array_agg(distinct prov||dpto||codloc||frac||radio||mza||lado),count(distinct lado) as lados FROM 
                        '.$esquema.'.
                        segmentacion s JOIN 
                        '.$esquema.'.
                        listado l ON s.listado_id=l.id 
                        GROUP BY segmento_id 
                        ORDER BY array_agg(mza),count(*), segmento_id ;');
     // SQL retrun: 
        }

}
