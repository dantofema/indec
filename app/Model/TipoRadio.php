<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TipoRadio extends Model
{
    //
    protected $table='tipo_de_radio';

    // Sin fecha de creación o modificación
    //
    public $timestamps = false;

    /**
     * Relación con Radios, una TipoRadio tiene varios radios.
     *
     */

    public function radios()
    {
        return $this->hasMany('App\Model\Radio','tipo_de_radio_id');
    }

}
