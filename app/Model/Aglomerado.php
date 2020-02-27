<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Aglomerado extends Model
{
    //
    protected $table='aglomerados';

    protected $fillable = [
        'id','codigo','nombre'
    ];
    public $carto;
    public $listado;
    public $segmentadolistado;

     /**
      * Relación con Localidades, un Aglomerados tiene una o varias localidad.
      *
      */

     public function localidades()
     {
         return $this->hasMany('App\Model\Localidad');
     }


    public function getCartoAttribute($value)
    {
    //select * from information_schema.tables where table_schema = 'e0777' and table_name = 'arc' and table_type = 'BASE TABLE'
        if (Schema::hasTable('e'.$this->codigo.'.arc')) {
            //
            return true;
        }else{
            return false;
        }
    }

    public function getListadoAttribute($value)
    {
        /// do your magic
        if (Schema::hasTable('e'.$this->codigo.'.listado')) {
            //
            return true;
        }else{
            return false;
        }
    }

    public function getSegmentadolistadoAttribute($value)
    {
        /// do your magic
        if (Schema::hasTable('e'.$this->codigo.'.segmentacion')) {
            //SELECT (count( distinct segmento_id)) segmentos,count(*) domicilios,round( (1.0*count(*))/(count( distinct segmento_id)) ,1) promedio  FROM e0777.segmentacion;
            return true;
        }else{
            return false;
        }
    }

    public function getSegmentadoladosAttribute($value)
    {
        /// do your magic
        if (Schema::hasTable('e'.$this->codigo.'.arc')) {
            $radios = DB::table('e'.$this->codigo.'.arc')
                         ->select(DB::raw("distinct substr(mzai,1,12) link"))
                         ->whereNotNull('segi')
                         ->orwhereNotNull('segd');
            if ($radios->count()>0){       
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function setCartoAtribute()
    {   return true;
        if (Schema::hasTable('e'.$this->codigo.'.arc')) {
            return $this->attributes['carto'] = true;
        }else{
            return $this->attributes['carto'] = false;
        }

    }

    public function getRadiosAttribute()
    {
        $radios= null;
        if ($this->Listado==1){
            $radios = DB::table('e'.$this->codigo.'.listado')
                                ->select(DB::raw("prov||dpto||frac||radio as link,codloc,'('||dpto||') '||nom_dpto||': '||frac||' '||radio as nombre,
             count(distinct mza) as cant_mzas,
             count(*) as vivs,
             count(CASE WHEN tipoviv='A' THEN 1 else null END) as vivs_a,
             count(CASE WHEN (tipoviv='B1' or tipoviv='B2') THEN 1 else null END) as vivs_b,
             count(CASE WHEN tipoviv='CA/CP' THEN 1 else null END) as vivs_c,
             count(CASE WHEN tipoviv='CO' THEN 1 else null END) as vivs_co,
             count(CASE WHEN (tipoviv='D'  or tipoviv='J'  or tipoviv='VE' )THEN 1 else null END) as vivs_djve,
             count(CASE WHEN tipoviv='' THEN 1 else null END) as vivs_unclas
    "))
                                ->groupBy('prov','dpto','codloc','nom_dpto','frac','radio')
                                ->get();
        }
        return $radios;

    }

    public function getSVG()
    {
        // return SVG Carto? Listado? Segmentación?
        // WITH shapes (geom, attribute) AS (a
        if ($this->carto){
            $svg=DB::statment("
WITH shapes (geom, attribute) AS (
  VALUES(
    (SELECT ST_MakeLine(ST_MakePoint(0,0), ST_MakePoint(10,10))), 2),
    ((SELECT ST_Envelope(ST_MakeBox2d(ST_MakePoint(0,0), st_makepoint(10,10)))), 3)
  ),
  paths (svg) as (
     SELECT concat(
         '<path d= \"', 
         ST_AsSVG(geom,1), '\" ', 
         CASE WHEN attribute = 0 THEN 'stroke=\"red\" stroke-width=\"3\" fill=\"none\"' 
         ELSE 'stroke=\"black\" stroke-width=\"2\" fill=\"green\"' END,
          ' />') 
     FROM shapes
 )
 SELECT concat(
         '<svg height=\"400\" width=\"450\">',
         array_to_string(array_agg(svg),''),
         '</svg>')
 FROM paths;
");
        dd($svg);
        }else{ return "No geodata"; }
                           
        
/*
  VALUES(
    (SELECT ST_MakeLine(ST_MakePoint(0,0), ST_MakePoint(10,10))), 2),
    ((SELECT ST_Envelope(ST_MakeBox2d(ST_MakePoint(0,0), st_makepoint(10,10)))), 3)
  ),
  paths (svg) as (
     SELECT concat(
         '<path d= "', 
         ST_AsSVG(geom,1), '" ', 
         CASE WHEN attribute = 0 THEN 'stroke="red" stroke-width="3" fill="none"' 
         ELSE 'stroke="black" stroke-width="2" fill="green"' END,
          ' />') 
     FROM shapes
 )
 SELECT concat(
         '<svg height="400" width="450">',
         array_to_string(array_agg(svg),''),
         '</svg>')
 FROM paths;
**/
    }

}
