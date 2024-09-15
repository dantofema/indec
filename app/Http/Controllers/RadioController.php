<?php

namespace App\Http\Controllers;

use App\Model\Radio;
use Illuminate\Http\Request;
use App\Segmentador;
use Illuminate\Support\Facades\Log;
 

class RadioController extends Controller
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
     * @param  \App\Model\Radio  $radio
     * @return \Illuminate\Http\Response
     */
    public function show(Radio $radio)
    {
        //
        return $radio->load(['fraccion','localidades','fraccion.departamento','fraccion.departamento.provincia','tipo']);
          flash(
                ($radio
                    ->load(['fraccion','localidades'])
                )->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
          )->important();
          Log::debug($radio->esquema);
          Log::debug(collect($radio->esquemas)->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
          return view('home');

    }

    public function show_codigo(string $codigo)
    {
        if (strlen($codigo)==9)
        {
            return Radio::where('codigo',$codigo)->get()
                ->load(['fraccion','localidades','tipo','fraccion.departamento','fraccion.departamento.provincia']);
        } else {
            Log::error('Código mal formado para radio',[$codigo]);
            return response()->json([
                'message' => 'Código mal formado.'
            ], 404);
        }
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

    /**
     * Juntar segmentos con menos de n viviendas 
     * 
     */
    public function juntarSegmentos(Radio $radio,$menos_n_viviendas)
    {
        //
        $aglo=$radio->aglomerado->codigo();
        $segmenta = new Segmentador();
        $segmenta->juntarSegmentos($radio,$menos_n_viviendas);
        return $segmenta->ver_segmentacion($radio);
    }

}
