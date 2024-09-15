<?php

namespace App\Http\Controllers;

use App\Model\Radio;
use Illuminate\Http\Request;
use App\Segmentador;
use App\Model\Localidad;
use App\Model\Departamento;

class RadiosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Localidad  $localidad
     * @param  \App\Model\Departamento  $departamento
     * @return \Illuminate\Http\Response
     */
    public function show(Localidad $localidad, Departamento $departamento)
    {
        //
        if($departamento->provincia->codigo=='02'){
            $radios= $departamento->radios;
        }else{
            $radios= $localidad->radios;
        }
        $aglomerado=$localidad->aglomerado;
        $carto=$aglomerado->Carto;
        $listado=$aglomerado->Listado;
        $svg=$aglomerado->getSVG();
        return view('radios.list_view',[
                    'aglomerado' => $aglomerado,
                    'carto' => $carto,
                    'listado'=>$listado,
                    'radios'=>$radios,
                    'localidad'=>$localidad,
                    'departamento'=>$departamento,
                    'svg'=>$svg]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Radio  $radio
     * @return \Illuminate\Http\Response
     */
    public function edit(Radio $radio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Radio  $radio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Radio $radio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Radio  $radio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Radio $radio)
    {
        //
    }

    /**
     * Segmentar radio a lados completos
     * 
     */
    public function segmentar(Radio $radio,$deseadas,$max,$min,$indivisible)
    {
        //
        $aglo=$radio->aglomerado->codigo();
        $segmenta = new Segmentador();
        $segmenta->segmentar_a_lado_completo($radio,$deseadas,$max,$min,$indivisible);
        return $segmenta->ver_segmentacion($radio);
    }

}
