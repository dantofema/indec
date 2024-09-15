<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\MyDB;
use App\Model\Aglomerado;
use App\Model\Radio;
use App\Model\Localidad;


class SegmentacionController extends Controller
{
    
    public function index(Aglomerado $aglomerado)
    {
        $nodos = MyDB::getNodos('e'.$aglomerado->codigo);
        $edges = MyDB::getAdyacencias('e'.$aglomerado->codigo);
        $segmentacion_data = MyDB::getSegmentos('e'.$aglomerado->codigo);
        $segmentacion=[];
        foreach ($segmentacion_data as $data){ 
                $segmentacion[]=explode(',',str_replace('}','',str_replace('{','',$data->segmento)));
                }
        return
        view('segmentacion.grafico2',['nodos'=>$nodos,'relaciones'=>$edges,'segmentacion'=>$segmentacion,'aglomerado'=>$aglomerado]);
    }
    
    public function ver_grafo_legacy(Aglomerado $aglomerado,Radio $radio)
    {
        // Funcion para mantener link redirect a una localidad...
          flash('Primer localidad del aglomerado');
          return redirect()->action(
                [SegmentacionController::class, 'ver_grafo'], 
                ['localidad'=>$aglomerado->localidades->first(),'radio'=>$radio])
          ->with('status', 'Legacy redirect a primer localidad del aglomerado!');
    }

    public function ver_grafo(Localidad $localidad,Radio $radio)
    {
        // Ver radio especificando el esquema de la localidad.
        $radio->esquema = 'e'.$localidad->codigo;
//        $aglomerado=$radio->getAglomerado();
//        $radio=$aglomerado->Localidades()->Radios()->first();
        $filtro_radio = substr($radio->codigo,0,5).'___'.substr($radio->codigo,5,4);
        $nodos = MyDB::getNodos($radio->esquema,$filtro_radio);
        $edges = MyDB::getAdyacencias($radio->esquema,$filtro_radio);
        $segmentacion_data = MyDB::getSegmentos($radio->esquema,$filtro_radio);
        $segmentacion=[];
        foreach ($segmentacion_data as $data){ 
                $segmentacion[]=explode(',',str_replace('}','',str_replace('{','',$data->segmento)));
                }
        $segmentacion_listado=MyDB::segmentar_equilibrado_ver($localidad->codigo,100,$radio);
//        $segmentacion_data_listado = json_encode ($segmentacion_listado, JSON_PRETTY_PRINT);

        $radio->refresh();
        return
        view('grafo.show',['nodos'=>$nodos,'relaciones'=>$edges,'segmentacion'=>$segmentacion,'segmentacion_data_listado'=>$segmentacion_listado,'aglomerado'=>$localidad->aglomerado,'localidad'=>$localidad,'radio'=>$radio]);
    }

    public function ver_grafico(Aglomerado $aglomerado,Radio $radio) {

                $segmentacion=MyDB::segmentar_lados_ver_resumen($aglomerado->codigo);
                return view('segmentacion.grafico2_lados',['segmentacion'=>$segmenta_data,'aglomerado'=>$aglomerado]);
    }

    public function ver(Localidad $localidad,Radio $radio)
      {
        // Ver radio especificando el esquema de la localidad.
        $radio->esquema = 'e'.$localidad->codigo;

        $filtro_radio = substr($radio->codigo,0,5).'___'.substr($radio->codigo,5,4);
        $nodos = MyDB::getNodos($radio->esquema,$filtro_radio);
        $edges = MyDB::getAdyacencias($radio->esquema,$filtro_radio);
        $segmentacion_data = MyDB::getSegmentos($radio->esquema,$filtro_radio);
        $segmentacion=[];
        foreach ($segmentacion_data as $data){ 
                $segmentacion[]=explode(',',str_replace('}','',str_replace('{','',$data->segmento)));
                }
        $segmentacion_listado=MyDB::segmentar_equilibrado_ver($localidad->codigo,100,$radio);
        $radio->refresh();
        return
        view('grafo.show',['nodos'=>$nodos,'relaciones'=>$edges,
             'segmentacion'=>$segmentacion,'segmentacion_data_listado'=>$segmentacion_listado,
             'aglomerado'=>$localidad->aglomerado,'radio'=>$radio]);
    }
}
