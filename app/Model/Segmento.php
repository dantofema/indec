<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Segmento extends Model
{
    protected $table= 'segmentos';
    public $fillable = [
            'prov',
            'nom_prov',
            'dpto',
            'nom_dpto',
            'codent',
            'nom_ent',
            'codloc',
            'nom_loc',
            'frac',
            'radio',
            'tipo',
            'seg',
            'vivs'
    ];


    static public function cargar_csv($file) 
    {
        $fileD = fopen($file, "r");
        $column=fgetcsv($fileD, 0, "|");
        while (!feof($fileD)) {
            $rowData=[];
            $rowData=fgetcsv($fileD, 0, "|");
            $inserted_data=[];
            if (is_array($rowData)) {
                foreach ($rowData as $key => $value_feature) {
                    $inserted_data[]=$value_feature;
                }          
                $item = new Segmento();
                $i = 0;
                foreach ($column as $col) {
                    $col=strtolower($col);
                    $item->$col = $inserted_data[$i];
                    $i++;
                }
                $segmento[]=$item;
            }
        }
        return $segmento;
    }

    public function show(Segmentos $segmentos)
    {
        return $segmentos;
    }

}
