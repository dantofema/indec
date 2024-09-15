<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Http\Client\ConnectionException;

class Provincia extends Model
{
    //
    protected $table='provincia';

    protected $fillable = [
        'id','codigo','nombre'
    ];

    protected $url_json = 'https://geoservicios.indec.gob.ar/geoserver/sig/ows';
    protected $params = ['service'=>'WFS','version'=>'1.0.0','request'=>'GetFeature',
    'typeName'=>'sig:v_provincias','outputFormat'=>'application/json'];
    protected $url_svg = 'http://172.26.68.22:8080/geoserver/precenso/wms';
    protected $params_svg = ['service'=>'WMS','version'=>'1.1.0','request'=>'GetMap','layers'=>'precenso:provincias',
    'bbox'=>'-73.9999999999999,-90.000000029,-24.9999999999999,-21.780856764','width'=>'551','height'=>'768',
    'srs'=>'EPSG:4326','format'=>'image/svg xml'];
    // Sin fecha de creación o modificación
    //
    public $timestamps = false;

    public function scopeFilter($query, $params)
    {
        if ( isset($params['name']) && trim($params['name'] !== '') ) {
            $query->where('nombre', 'LIKE', trim($params['name']) . '%');
        }

        //$query->where('operativo.',) // Operativo x default.

        return $query;
    }

     /**
     * Get the departamentos de la provincia.
     */
    public function departamentos()
    {
        return $this->hasMany('App\Model\Departamento');
    }

    /**
     * Relación con Fracciones, a través de departamentos puede tener muchas fracciones.
     */
    public function fracciones()
    {
        return $this->hasManyThrough('App\Model\Fraccion','App\Model\Departamento');
    }

    /**
     * Relación con Fracciones, a través de departamentos puede tener muchas fracciones.
     */
//    public function radios()
//    {
//        return $this->hasManyThrough('App\Model\Radio','App\Model\Departamento');
//    }

    /**
     * Relación con Operativo, diretamente puede tener varios operativos.
     */
    public function operativos()
    {
        return $this->hasMany('App\Model\OperativoProv');
    }

    /**
     * Get the json geoservicios de la provincia.
     */
    public function geojson()
    {
      try {
        $response = Http::timeout(5)->get(
          $this->url_json,
          Arr::add($this->params,"cql_filter","link =  '".$this->codigo."'")
        );
      } catch ( ConnectionException $e) {
        flash('Timeout de 5 seg. a: '.$this->url_json)->warning();
        return null;
      }
         if ($response->ok()) {
           return $response;
         } else {
           return $response->headers()->json;
         }

    }


    /**
     * Get the json geoservicios de la provincia.
     */
    public function svg()
    {
      try {
       $response = Http::timeout(5)->get($this->url_svg,
         Arr::add($this->params_svg,"cql_filter","link =  '".$this->codigo."'")
        );
      } catch ( ConnectionException $e) {
        flash('Timeout de 5 seg. a: '.$this->url_svg)->warning();
        return null;
      }
        if ($response->ok()) {
          return $response;
        } else {
          return $response->headers();
        }
    }

}
