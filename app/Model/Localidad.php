<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Localidad extends Model
{
    //
    protected $table = 'localidad';

    protected $fillable = [
        'codigo','nombre'
    ];

    // Sin fecha de creación o modificación
    //
    public $timestamps = false;
    private $_carto;
    private $_listado;
    public $segmentadoListado;
    public $segmentadoLados;

    /**
     * Relación con Departamento, una localidad puede estar en muchos departamentos (caso CABA).
     */
    public function departamentos()
    {
        return $this->belongsToMany('App\Model\Departamento','localidad_departamento');
        // en el caso de CABA
    }

    /**
     * Relación con Aglomerados, una localidad pertenece a un aglomerado.
     */

    public function aglomerado()
    {
        return $this->belongsTo('App\Model\Aglomerado');
    }

    /**
     * Relación con Radios, una localidad tiene muchos radios (que pueden estas en mas de una localidad?).
     */

    public function radios()
    {
        return $this->belongsToMany('App\Model\Radio', 'radio_localidad');
    }

    //
    public function getCodigoLocAttribute($value){
        return $codloc= substr(trim($this->codigo), 5, 3);
    }

    public function getCartoAttribute($value)
    {
        if(! $this->_carto) {
            if (Schema::hasTable('e'.$this->codigo.'.arc')) {
                $this->_carto = true;
            }else{
                $this->_carto = false;
            }
        }
        return $this->_carto;
    }
    

    public function getListadoAttribute($value)
    {
        /// do your magic
        if (! $this->_listado) {
            if (Schema::hasTable('e'.$this->codigo.'.listado')) {
                //
                $this->_listado= true;
            }else{
                $this->_listado= false;
            }
        }
        return $this->_listado;
    }

    public function getSegmentadolistadoAttribute($value)
    {
        /// do your magic
        if (! $this->segmentadoListado) {
          if (Schema::hasTable('e'.$this->codigo.'.segmentacion')) {
              return $this->segmentadoListado = true;
          }else{
              return $this->segmentadoListado = false;
          }
        }
        return $this->segmentadoListado;
    }

    public function getSegmentadoladosAttribute($value)
    {
        /// do your magic
        if (! $this->segmentadoLados) {
          if (Schema::hasTable('e'.$this->codigo.'.arc')) {
              $radios = DB::table('e'.$this->codigo.'.arc')
                           ->select(DB::raw("distinct substr(mzai,1,12) link"))
                           ->whereNotNull('segi')
                           ->orwhereNotNull('segd');
              if ($radios->count()>0){
                  return $this->segmentadoLados = true;
              }else{
                  return $this->segmentadoLados = false;
              }
          }else{
              return $this->segmentadoLados = false;
          }
        }
        return $this->segmentadoLados;
    }

    public function setCartoAtribute()
    {
        if (! $this->_carto) {
          if (Schema::hasTable('e'.$this->codigo.'.arc')) {
              return $this->_carto = true;
          }else{
              return $this->_carto = false;
          }
        }
        return $this->_carto;

    }

    public function getRadiosAttribute()
    {
        $radios= null;
        if ($this->Listado==1){
            $radios = DB::table('e'.$this->codigo.'.listado')
                                ->select(DB::raw("prov||dpto||frac||radio as link,codloc,
             '('||dpto||') '||max(nom_dpto)||': '||frac||' '||radio as nombre,
             count(distinct mza) as cant_mzas,
             count(*) as registros,
             count(indec.contar_vivienda(tipoviv)) as vivs,
             count(CASE WHEN tipoviv='A' THEN 1 else null END) as vivs_a,
             count(CASE WHEN (tipoviv='B1' or tipoviv='B2') THEN 1 else null END) as vivs_b,
             count(CASE WHEN tipoviv='CA/CP' THEN 1 else null END) as vivs_c,
             count(CASE WHEN tipoviv='CO' THEN 1 else null END) as vivs_co,
             count(CASE WHEN (tipoviv='D'  or tipoviv='J'  or tipoviv='VE' )THEN 1 else null END) as vivs_djve,
             count(CASE WHEN tipoviv='' THEN 1 else null END) as vivs_unclas
            "))
                                ->groupBy('prov','dpto','codloc','frac','radio')
                                ->orderBy('prov','asc')
                                ->orderBy('dpto','asc')
                                ->orderBy('codloc','asc')
                                ->orderBy('frac','asc')
                                ->orderBy('radio','asc')
                                ->get();
        }
            $links=[];
            $new_radios=[];
            $objRadios= Collect (new Radio);
            $nuevos_radios=0;
//            $objRadios=Radio::whereIn('codigo',$radios);
//            flash($radios->get('link').'a')->important();
        if($radios){
            foreach($radios as $radio){
  //              if (Radio::where('codigo',$radio->link)->exists()){
                               $links[]=$radio->link;
  //              }else{
  //                  $new_radios[]=new Radio (['codigo'=>$radio->link,'nombre'=>'Nuevo x listado']);
  //                  $nuevos_radios++;
  //                flash('No se encontró radio  -> '.$radio->link.': Revisar y cargar PxRad ')->error()->important();
  //              }
            }
        }
        if (count($links)>0){
            $objRadios=Radio::whereIn('codigo',$links)
                        ->get();
            if($objRadios->count()!=count($radios)){
               flash('Radios existentes verificados: '.$objRadios->count().'. Radios encontrados en listado: '.count($radios))->warning()->important();
               // TODO: Crear un nuevo radio para el radio encontrado, o avisar cuál es.
            }else{
               flash('Radios existentes verificados -> '.$objRadios->count())->success();
            }
        }
            //dd($new_radios,$nuevos_radios);
            $objs=$objRadios->union(Collect ($new_radios))->sortBy('codigo');
        return $objs;

    }

    public function getComboRadiosAttribute()
    {
        $radios= null;
        if ($this->Listado==1){
            $radios = DB::table('e'.$this->codigo.'.listado')
                                ->select(DB::raw("prov||dpto||frac||radio as link,codloc,
             '('||dpto||') F'||frac||' R'||radio as nombre,
             count(distinct mza) as cant_mzas,
             count(*) as registros,
             count(indec.contar_vivienda(tipoviv)) as vivs,
             count(CASE WHEN tipoviv='A' THEN 1 else null END) as vivs_a,
             count(CASE WHEN (tipoviv='B1' or tipoviv='B2') THEN 1 else null END) as vivs_b,
             count(CASE WHEN tipoviv='CA/CP' THEN 1 else null END) as vivs_c,
             count(CASE WHEN tipoviv='CO' THEN 1 else null END) as vivs_co,
             count(CASE WHEN (tipoviv='D'  or tipoviv='J'  or tipoviv='VE' )THEN 1 else null END) as vivs_djve,
             count(CASE WHEN tipoviv='' THEN 1 else null END) as vivs_unclas
    "))
                                ->groupBy('prov','dpto','codloc','frac','radio')
                                ->orderBy('prov','asc')
                                ->orderBy('dpto','asc')
                                ->orderBy('codloc','asc')
                                ->orderBy('frac','asc')
                                ->orderBy('radio','asc')
                                ->get();
        }
        return $radios;
    }

    public function getSVGRadios()
    {
        // return SVG Con Radios
        // WITH shapes (geom, attribute) AS (a

       if (Schema::hasTable('e'.$this->codigo.'.radios')) {
        $height=800;
        $width=900;
        $escalar=false;
            $extent=DB::select("SELECT box2d(st_collect(wkb_geometry)) box FROM  e".$this->codigo.".radios");
            $extent=$extent[0]->box;
            list($x0,$y0,$x1,$y1) = sscanf($extent,'BOX(%f %f,%f %f)');

             $Dx=$x1-$x0; $Dy=$y1-$y0;
            if (!$height and $width) $height=round($width*$Dy/$Dx);
            if ($height and !$width) $width=round($height*$Dx/$Dy);
            if (!$height and !$width) {$width=round($perimeter*$Dx/2/($Dx+$Dy)); $height=round($width*$Dy/$Dx);}
            $dx=$Dx/$width; $dy=$Dy/$height; $epsilon=min($dx,$dy)/15; // mínima reolución, acho y alto de lo que representa un pixel
            if ($escalar) {$viewBox="0 0 $width $height"; $stroke=2;}
            else { $viewBox=$this->viewBox($extent,$epsilon,$height,$width); $stroke=2*$epsilon;
        }

            //dd($viewBox.'/n'.$this->viewBox($extent,$epsilon,$height,$width).'/n'.$x0." -".$y0." ".$x1." -".$y1);
            $svg=DB::select("
WITH shapes (geom, attribute,bgcolor) AS (
    ( SELECT wkb_geometry, gid,'none' 
    FROM e".$this->codigo.".radios)
  ),
  paths (svg) as (
     SELECT concat(
         '<path d= \"',
         ST_AsSVG(st_buffer(st_union(geom),-5),0), '\" ',
         CASE WHEN attribute = 0 THEN 'stroke=\"gray\" stroke-width=\"2\"
         fill=\"gray\"'
              WHEN attribute < 5 THEN 'stroke=\"none\"
              stroke-width=\"".$stroke."\" fill=\"#' || attribute*20 || 'AAAA\"'
              WHEN attribute < 10 THEN 'stroke=\"none\"
         stroke-width=\"".$stroke."\" fill=\"#00' || (attribute-5)*20 || '00\"'
              WHEN attribute < 15 THEN 'stroke=\"none\"
         stroke-width=\"".$stroke."\" fill=\"#AA' || (attribute-10)*20 || '00\"'
         ELSE
            'stroke=\"black\" stroke-width=\"".$stroke."\" fill=\"#22' ||
            attribute*10 || '88\"'
         END,
          ' />') 
     FROM shapes GROUP BY attribute
 )
 SELECT concat(
         '<svg id=\"localidad_".$this->codigo."\"xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"".$viewBox.
         "\" height=\"".$height."\" width=\"".$width."\">',
         array_to_string(array_agg(svg),''),
         '</svg>') 
 FROM paths;
");
        if ($svg[0]->concat) {
          return $svg[0]->concat;
        }else{ return "no SVG"; }
     }else{ return "No se pueden previsualizar los radios"; }

    }

    public function getSVG()
    {
        // return SVG Carto? Listado? Segmentación?
        // WITH shapes (geom, attribute) AS (a
      if ($this->Carto){
        if ($this->radios->count()>20){
           return $this->getSVGRadios();
           return __('Demasiados radios para previsualizar, son más de 50');
        }
        $height=800;
        $width=900;
        $escalar=false;
            $extent=DB::select("SELECT box2d(st_collect(wkb_geometry)) box FROM  e".$this->codigo.".arc");
            $extent=$extent[0]->box;
            list($x0,$y0,$x1,$y1) = sscanf($extent,'BOX(%f %f,%f %f)');

             $Dx=$x1-$x0; $Dy=$y1-$y0;
            if (!$height and $width) $height=round($width*$Dy/$Dx);
            if ($height and !$width) $width=round($height*$Dx/$Dy);
            if (!$height and !$width) {$width=round($perimeter*$Dx/2/($Dx+$Dy)); $height=round($width*$Dy/$Dx);}
            $dx=$Dx/$width; $dy=$Dy/$height; $epsilon=min($dx,$dy)/15; // mínima reolución, acho y alto de lo que representa un pixel
            if ($escalar) {$viewBox="0 0 $width $height"; $stroke=2;}
            else { $viewBox=$this->viewBox($extent,$epsilon,$height,$width); $stroke=2*$epsilon;
        }

            //dd($viewBox.'/n'.$this->viewBox($extent,$epsilon,$height,$width).'/n'.$x0." -".$y0." ".$x1." -".$y1);
            $svg=DB::select("
WITH shapes (geom, attribute) AS (
    ( SELECT st_buffer(ST_OffsetCurve(wkb_geometry,5),2) wkb_geometry, segi
    FROM e".$this->codigo.".arc WHERE segi is not null)
    UNION (
    SELECT st_buffer(ST_OffsetCurve(wkb_geometry,-5),2) wkb_geometry, segd
    FROM e".$this->codigo.".arc WHERE segd is not null)
    UNION (
    SELECT st_buffer(ST_OffsetCurve(wkb_geometry,-5),2) wkb_geometry, 0
    FROM e".$this->codigo.".arc WHERE segd is null)
    UNION (
    SELECT st_buffer(ST_OffsetCurve(wkb_geometry,5),2) wkb_geometry, 0
    FROM e".$this->codigo.".arc WHERE segi is null)
  ),
  paths (svg) as (
     SELECT concat(
         '<path d= \"',
         ST_AsSVG(st_buffer(st_union(geom),5),0), '\" ',
         CASE WHEN attribute = 0 THEN 'stroke=\"gray\" stroke-width=\"2\"
         fill=\"gray\"'
              WHEN attribute < 5 THEN 'stroke=\"none\"
              stroke-width=\"".$stroke."\" fill=\"#' || attribute*20 || 'AAAA\"'
              WHEN attribute < 10 THEN 'stroke=\"none\"
         stroke-width=\"".$stroke."\" fill=\"#00' || (attribute-5)*20 || '00\"'
              WHEN attribute < 15 THEN 'stroke=\"none\"
         stroke-width=\"".$stroke."\" fill=\"#AA' || (attribute-10)*20 || '00\"'
         ELSE
            'stroke=\"black\" stroke-width=\"".$stroke."\" fill=\"#22' ||
            attribute*10 || '88\"'
         END,
          ' />') 
     FROM shapes GROUP BY attribute
 )
 SELECT concat(
         '<svg id=\"localidad_".$this->codigo."\"xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"".$viewBox.
         "\" height=\"".$height."\" width=\"".$width."\">',
         array_to_string(array_agg(svg),''),
         '</svg>') 
 FROM paths;
");
        if ($svg[0]->concat) {
          return $svg[0]->concat;
        }else{ return "no SVG"; }
        }else{ return "No se puede previsualizar"; }

    }

    private function viewBox($extent,$epsilon,$height,$width){
        list ( $x0, $y0, $x1, $y1 ) = sscanf ( $extent, 'BOX(%f %f,%f %f)' );
        $Dx = $x1 - $x0;
        $Dy = $y1 - $y0;
            $m_izq=.1*$Dx; $m_der=.1*$Dx; $m_arr=.1*$Dy; $m_aba=.1*$Dy;
        $viewBox = ($x0 - $m_izq) . " " . (- $y1 - $m_arr) . " " . ($Dx + $m_izq + $m_der) . " " . ($Dy + $m_arr + $m_aba);
        if (! $height and ! $width)
            $height = 600;
        if (! $height)
            $height = $width * $Dy / $Dx;
        if (! $width)
           $width = $height * $Dx / $Dy;
        $epsilon = min ( $Dx / $width, $Dy / $height );
        return $viewBox;
    }


}
