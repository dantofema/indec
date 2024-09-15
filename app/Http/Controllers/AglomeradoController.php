<?php
namespace App\Http\Controllers;

use App\Model\Aglomerado;
use App\Model\Radio;
use Illuminate\Http\Request;
use App\MyDB;
use Illuminate\Support\Facades\Log;

    class AglomeradoController extends Controller
    {
        /**
        * Display a listing of the resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function index()
        {
            // Listado de Aglomerados.
            return view('aglos');
        }

        public function aglosList()
        {
            $aglosQuery = Aglomerado::query();
            $codigo = (!empty($_REQUEST["codigo"])) ? ($_REQUEST["codigo"]) : ('');
            if($codigo){
            $aglosQuery->where('codigo','=',$codigo);
            }
            $aglos = $aglosQuery->select('*', \DB::raw('false carto,false
            listado,false segmentadolistado,false segmentadolados'))
                                ->withCount(['localidades'])
                                ->where('codigo','not like','0000');
    //                            ->withCount(['radios']);
    //        $carto=$aglos->Carto;
    //        $listado=$aglos->Listado;
            return datatables()->of($aglos)
    /*            ->addColumn('actions', function ($data) {
                    return "<a class='btn btn-xs btn-success' href='/segmentar/$data->id'>Segmentar</a>";
                })
    */
                ->make(true);
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
        * @param  \App\Model\Aglomerado  $aglomerado
        * @return \Illuminate\Http\Response
        */
        public function show(Aglomerado $aglomerado)
        {
            //
            if($aglomerado->Localidades()->count()==1) {
                return redirect()->action(
                  [LocalidadController::class, 'show'], [$aglomerado->Localidades()->first()]
                  );
/*                $carto=$aglomerado->Carto;
                $listado=$aglomerado->Listado;
                $radios=$aglomerado->Radios;
                $svg=$aglomerado->getSVG();
                return view('aglo.segmenta_view',[
                            'aglomerado' => $aglomerado,
                            'carto' => $carto,
                            'listado'=>$listado,
                            'radios'=>$radios,
                            'svg'=>$svg]);*/
            }else{
                $aglomerado->load('localidades');
                return view('aglo.localidades_view',[
                            'aglomerado'=>$aglomerado]);
            }
        }
        
        public function show_post(Aglomerado $aglomerado)
        {
            //
            return view('aglo.info',['aglomerado' => $aglomerado]);
        }

        public function segmenta_post(Aglomerado $aglomerado)
        {
            // Si el Aglomerado es una sola localidad
            // Entonces redireccion al metodo de la localidad
            if($aglomerado->Localidades()->count()==1){
                return redirect()->action(
                      [LocalidadController::class, 'segmenta_post'], [$aglomerado->Localidades()->first()]
                    );
            }else{
              $carto=$aglomerado->Carto;
              $listado=$aglomerado->Listado;
              $radios=$aglomerado->ComboRadios;
              return view('aglo.segmenta',['aglomerado' => $aglomerado,'carto' => $carto,'listado'=>$listado,'radios'=>$radios]);
            }
        }

        public function run_segmentar(Request $request, Aglomerado $aglomerado)
        {
            if($request->optalgoritmo=='listado'){
                // Segmentacion x listado
                return $this->run_segmentar_equilibrado($request,$aglomerado); 
            }elseif($request->optalgoritmo=='lados'){
                // Segmentacion x lado completo. Esto se realiza x radio o puedo hacerse para los radios del request.
                return $this->run_segmentar_x_lado($request,$aglomerado); 
                }else{
                    $radio=null;
                    $result =
                    $this->run_segmentar_x_lado($request,$aglomerado,$radio,true); 
                    Log::debug("Segmentacion lucky: ".$radio);
                    $excedidos =
                    MyDB::segmentos_excedidos('e'.$aglomerado->codigo,$request['vivs_max'],$radio);
                    $mensajes_excedidos = '';
                    foreach ($excedidos as $segmento){
			    $mensajes_excedidos .= 'El lado '.$segmento->lado.' de la manzana '.$segmento->mza.
				    ' debe segmentarse x listado, ya que tienen '.$segmento->vivs.' viviendas.
                                    ';
                        Log::debug(json_encode($segmento));
                    }
                    if ($radio){
                        if ($mensajes_excedidos!=''){
                        $radio->resultado.= '
    '.$mensajes_excedidos;
                        }
//                        $this->run_segmentar_x_lucky($request,$aglomerado,$radio,true); 
//                        $radio->segmentarLucky($request['vivs_max'],$request['vivs_deseadas']);
                        return app('App\Http\Controllers\SegmentacionController')->ver_grafo($aglomerado,$radio);        
                    }
                //dd($aglomerado);
              return
              app('App\Http\Controllers\SegmentacionController')->index($aglomerado);        
            }

    }
    public function run_segmentar_equilibrado(Request $request, Aglomerado $aglomerado)
    {
        if(MyDB::segmentar_equilibrado($aglomerado->codigo,$request['vivs_deseadas'])) {
           flash('Segmentado ('.$aglomerado->codigo.') '.$aglomerado->nombre.'!'); 
           return redirect()->route('ver-segmentacion', [$aglomerado]); //$this->ver_segmentacion($aglomerado);
        };
        
    }

    public function ver_segmentacion(Aglomerado $aglomerado)
    {
            $segmentacion=MyDB::segmentar_equilibrado_ver($aglomerado->codigo);
            $segmenta_data = json_encode ($segmentacion);
            return view('segmentacion.info',['segmentacion'=>$segmenta_data,'aglomerado'=>$aglomerado]);
    }

    public function ver_segmentacion_lados(Aglomerado $aglomerado)
    {
            $segmentacion=MyDB::segmentar_lados_ver($aglomerado->codigo);
            $segmenta_data = json_encode ($segmentacion);
            return view('segmentacion.lados_info',['segmentacion'=>$segmenta_data,'aglomerado'=>$aglomerado]);
    }

    public function ver_segmentacion_grafico(Request $request, Aglomerado $aglomerado)
    {
            $segmentacion=MyDB::segmentar_equilibrado_ver($aglomerado->codigo);
            $segmenta_data = json_encode ($segmentacion);
            if ($request->isMethod('post')) {
                return response()->json($segmentacion);
            }else{
                return view('segmentacion.grafico',['segmentacion'=>$segmenta_data,'aglomerado'=>$aglomerado]);
            }
    }

    public function ver_segmentacion_grafico_resumen(Request $request, Aglomerado $aglomerado)
    {
            $segmentacion=MyDB::segmentar_equilibrado_ver_resumen($aglomerado->codigo);
            $segmenta_data = json_encode ($segmentacion);
            if ($request->isMethod('post')) {
                return response()->json($segmentacion);
            }else{
                return view('segmentacion.grafico2',['segmentacion'=>$segmenta_data,'aglomerado'=>$aglomerado]);
            }
    }

    public function ver_segmentacion_lados_grafico_resumen(Request $request, Aglomerado $aglomerado)
    {
            $segmentacion=MyDB::segmentar_lados_ver_resumen($aglomerado->codigo);
            $segmenta_data = json_encode ($segmentacion);
            if ($request->isMethod('post')) {
                return response()->json($segmentacion);
            }else{
                return view('segmentacion.grafico2_lados',['segmentacion'=>$segmenta_data,'aglomerado'=>$aglomerado]);
            }
    }

    public function run_segmentar_x_lado(Request $request, Aglomerado
    $aglomerado,Radio &$radio=null,$lucky=null)
    {
      if($request->checkallradios){
        Log::debug('Se van a segmentar todos los radios del
        aglomerado: ('.$aglomerado->codigo.') '.$aglomerado->nombre );
      }
        elseif($request->radios){
           $radio= Radio::where('codigo',$request->radios)->first();
            if ($radio==null){
                $radio = new Radio(['id'=>null,'codigo'=>$request->radios,'nombre'=>'Nuevo: '.$request->radios]);
                Log::debug('No se encontró el radio: '.$request->radios.' Se
                crea temporalmente.');
            }
            if ($lucky!=true){
           $resultado = $radio->segmentar($aglomerado->codigo,
                                          $request['vivs_deseadas'],
                                          $request['vivs_max'],
                                          $request['vivs_min'],
                                          $request['mzas_indivisibles']);
                                          }else{
           $resultado = $radio->segmentarLucky($aglomerado->codigo,
                                          $request['vivs_deseadas'],
                                          $request['vivs_max'],
                                          $request['vivs_min'],
                                          $request['mzas_indivisibles']);
                                          }
            return  app('App\Http\Controllers\SegmentacionController')->ver_grafo($aglomerado,$radio);        
        }else{
           flash('No selecciono ningún radio valido!'); 
           dd($request);
        }
        
    }

    public function ver_pxseg(Aglomerado $aglomerado)
    {
            $pxseg=MyDB::getPxSeg('e'.$aglomerado->codigo);
            $data = collect($pxseg); //json_encode ($pxseg);
            return view('segmentacion.pxseg',['pxseg'=>$data,'aglomerado'=>$aglomerado]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Aglomerado  $aglomerado
     * @return \Illuminate\Http\Response
     */
    public function edit(Aglomerado $aglomerado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Aglomerado  $aglomerado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aglomerado $aglomerado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Aglomerado  $aglomerado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aglomerado $aglomerado)
    {
        //
    }
}
