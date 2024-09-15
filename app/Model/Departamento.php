<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //
    protected $table = 'departamentos';

    protected $fillable = [
        'codigo','nombre','provincia_id'
    ];

    // Sin fecha de creación o modificación
    //
    public $timestamps = false;

    /**
     * Obtener la provicnia a donde pertencen el Departamento.
     */
    public function provincia()
    {
        return $this->belongsTo('App\Model\Provincia');
    }

    /**
     * Relación con Localidades, un departamentos puede tener muchos localidades.
     */
    public function localidades()
    {
        return $this->belongsToMany('App\Model\Localidad','localidad_departamento');
    }

    /**
     * Relación con Fracciones, un departamentos puede tener muchas fracciones.
     */
    public function fracciones()
    {
        return $this->hasMany('App\Model\Fraccion');
    }

    /**
     * Relación con Radios, un departamentos puede tener muchos Radios a traves
     * de las Fracciones.
     */
    public function radios()
    {
        return $this->hasManyThrough('App\Model\Radio','App\Model\Fraccion');
    }

    /**
     * Denominación según provincia: departamento, comuna o partido.
     *
     */
    public function getDenominacionAttribute()
    {
  	    if($this->provincia->codigo=='02')
	    	    return 'Comuna';
	      elseif($this->provincia->codigo=='06')
    		    return 'Partido';
  	    else {
	    	    return 'Departamento';
  	    }
    }
}
