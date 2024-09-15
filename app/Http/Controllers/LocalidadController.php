<?php

namespace App\Http\Controllers;

use App\Model\Localidad;
use App\Model\Radio;
use App\MyDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use DataTables;

class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  
            $localidades=Localidad::all();
            return $localidades;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //  
            return view('locas');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function locasList()
    {
           $model = Localidad::with(['aglomerado', 'departamentos.provincia' ,'departamentos'])
               ->withCount(['radios'])
               ->where('codigo', 'not like', '%000');
           $codigo = (!empty($_REQUEST["codigo"])) ? ($_REQUEST["codigo"]) : ('');
        if ($codigo) {
            $model->where('codigo', '=', $codigo);
        }
        return DataTables::eloquent($model)
            ->addColumn( 
                'departamento', function (Localidad $loc) {
                    if ( $loc->departamentos->first() ) {
                      return $loc->departamentos->first()->nombre;
                    } else {
                      Log::debug('Localidad sin depto '.$loc->codigo);
                      return '(no definido)';
                    }
                }
            )
            ->addColumn(
                'provincia', function (Localidad $loc) {
                    if ( $loc->departamentos->first() ) {
                      if ( $loc->departamentos->first()->provincia ) {
                        return $loc->departamentos->first()->provincia->nombre;
                      } else {
                        Log::debug('Localidad sin provincia '.$loc->codigo);
                        return '(no definido)';
                      }
                    }
                }
            )
            ->addColumn(
                'aglomerado', function (Localidad $loc) {
                    if ( $loc->aglomerado ) {
                      return '(' . $loc->aglomerado->codigo . ') ' . $loc->aglomerado->nombre;
                    } else {
                      Log::debug('Localidad sin aglo'.$loc->codigo);
                      return '(no definido)';
                    }
                }
            )
            ->toJson();
        /*            $locasQuery = Localidad::query();
            $codigo = (!empty($_REQUEST["codigo"])) ? ($_REQUEST["codigo"]) : ('');
        if ($codigo) {
             $locasQuery->where('codigo', '=', $codigo);
        }
            $locas = $locasQuery->select('*')
                                ->with(['departamentos'])
                                ->withCount(['radios'])
                                ->where('codigo','not like','%000');
            return datatables()->of($locas)
                ->make(true);
        */
    }


    public function show_codigo(Request $request, string $codigo)
    {
        if (strlen($codigo)==8)
        {
            $model = Localidad::where('codigo',$codigo) 
                ->with(['aglomerado','departamentos','departamentos.provincia'])
                ->withCount(['radios'])
                ->get();
            if ( $request->isMethod('get') ){
              // Redirige al primer registro encontrado con ese código.
              return redirect()->action([LocalidadController::class,'show'], [$model->first()] );
            } else {
              return $model;
            }

        } else {
            Log::error('Código mal formado para localidad',[$codigo]);
            return response()->json([
                'message' => 'Código mal formado.'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     
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
     * @return \Illuminate\Http\Response
     */
    public function show(Localidad $localidad)
    {
        //p
            if ($localidad->departamentos()->count()>1) {
                flash($localidad->nombre.' MULTIDEPARTAMENTAL ')->success();
                return view('localidad.deptos_view',[
                        'localidad'=>$localidad,
                        'deptos'=>$localidad->departamentos()->get(),
                        'carto'=>null,'listado'=>null
                        ]);

            }
            //radios_loc definido para localidades sin radios cargados
            $radios_loc = array();
            foreach($localidad->radios as $radio){$radio->esquema='e'.$localidad->codigo;$radios_loc[]=$radio;}
            return view('localidad.radios',[
                        'localidad'=>$localidad,
                        'aglomerado'=>$localidad->aglomerado,
                        'radios'=>$radios_loc,
                        'carto'=>$localidad->Carto,
                        'listado'=>$localidad->Listado,
                        'svg'=>$localidad->getSVG()
                        ]);
    }

    public function segmenta_post(Localidad $localidad)
    {
            //
            $carto=$localidad->Carto;
            $listado=$localidad->Listado;
            $radios=$localidad->ComboRadios;
            return view('localidad.segmenta',['localidad' => $localidad,'carto' => $carto,'listado'=>$listado,'radios'=>$radios]);
    }

    public function run_segmentar(Request $request, Localidad $localidad)
    {
            if($request->optalgoritmo=='listado'){
                // Segmentacion x listado
                return $this->run_segmentar_equilibrado($request,$localidad);
            }elseif($request->optalgoritmo=='lados'){
                // Segmentacion x lado completo. Esto se realiza x radio o puedo hacerse para los radios del request.
                return $this->run_segmentar_x_lado($request,$localidad);
                }else{
                    $radio=null;
                    $result =
                    $this->run_segmentar_x_lado($request,$localidad,$radio,true);
                    Log::debug("Segmentacion lucky: ".$radio);
                    $excedidos =
                    MyDB::segmentos_excedidos('e'.$localidad->codigo,$request['vivs_max'],$radio);
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
                        $radio->esquema='e'.$localidad->codigo;
                        return app('App\Http\Controllers\SegmentacionController')->ver($localidad,$radio);
                    }
              return
              app('App\Http\Controllers\SegmentacionController')->index($localidad);
            }
    }

    public function run_segmentar_equilibrado(Request $request, Localidad $localidad)
    {
      if (Auth::check()) {
        $AppUser= Auth::user();
        if($request->radios){
           $radio= Radio::where('codigo',$request->radios)->first();
            if ($radio==null){
                $radio = new Radio(['id'=>null,'codigo'=>$request->radios,'nombre'=>'Nuevo al segmentar: '.$request->radios]);
            }
            if(MyDB::segmentar_equilibrado($localidad->codigo,$request['vivs_deseadas'],$radio)) {
               flash('Segmentado el Radio '.$radio->codigo.' de ('.$localidad->codigo.') '.$localidad->nombre.
                     ' a '.$request['vivs_deseadas'].' viviendas!');
                $radio->resultado = 'Segmentado a manzana independiente a '.$request['vivs_deseadas'].' viviendas deseadas.
        x '.$AppUser->name.' ('.$AppUser->email.') en '.date("Y-m-d H:i:s").
  '
  ----------------------- LOG ----------------------------
  '.$radio->resultado;
        $radio->save();
             return app('App\Http\Controllers\SegmentacionController')->ver($localidad,$radio);
            }
        }else{
          if(MyDB::segmentar_equilibrado($localidad->codigo,$request['vivs_deseadas'])) {
             flash('Segmentada ('.$localidad->codigo.') '.$localidad->nombre.' completa!');
             return redirect()->route('ver-segmentacion', [$localidad]); 
          };
        }
      }else{
        return 'No tiene permiso para segmentar';
      }
    }

    public function ver_segmentacion(Localidad $localidad)
    {
            $segmentacion=MyDB::segmentar_equilibrado_ver($localidad->codigo);
            $segmenta_data = json_encode ($segmentacion);
            return view('segmentacion.info',['segmentacion'=>$segmenta_data,'localidad'=>$localidad]);
    }

    public function ver_segmentacion_lados(Localidad $localidad)
    {
            $segmentacion=MyDB::segmentar_lados_ver($localidad->codigo);
            $segmenta_data = json_encode ($segmentacion);
            return view('segmentacion.lados_info',['segmentacion'=>$segmenta_data,'localidad'=>$localidad]);
    }

    public function ver_segmentacion_grafico(Request $request, Localidad $localidad)
    {
            $segmentacion=MyDB::segmentar_equilibrado_ver($localidad->codigo);
            $segmenta_data = json_encode ($segmentacion);
            if ($request->isMethod('post')) {
                return response()->json($segmentacion);
            }else{
                return view('segmentacion.grafico',['segmentacion'=>$segmenta_data,'localidad'=>$localidad]);
            }
    }

    public function ver_segmentacion_grafico_resumen(Request $request, Localidad $localidad)
    {
            $segmentacion=MyDB::segmentar_equilibrado_ver_resumen($localidad->codigo);
            $segmenta_data = json_encode ($segmentacion);
            if ($request->isMethod('post')) {
                return response()->json($segmentacion);
            }else{
                return view('segmentacion.grafico2',['segmentacion'=>$segmenta_data,'localidad'=>$localidad]);
            }
    }

    public function ver_segmentacion_lados_grafico_resumen(Request $request, Localidad $localidad)
    {
            $segmentacion=MyDB::segmentar_lados_ver_resumen($localidad->codigo);
            $segmenta_data = json_encode ($segmentacion);
            if ($request->isMethod('post')) {
                return response()->json($segmentacion);
            }else{
                return view('segmentacion.grafico2_lados',['segmentacion'=>$segmenta_data,'localidad'=>$localidad]);
            }
    }


    public function run_segmentar_x_lado(Request $request, Localidad
    $localidad,Radio &$radio=null,$lucky=null)
    {
      if($request->checkallradios){
        Log::debug('Se van a segmentar todos los radios de la
        localidad: ('.$localidad->codigo.') '.$localidad->nombre );
      }
        elseif($request->radios){
           $radio= Radio::where('codigo',$request->radios)->first();
            if ($radio==null){
                $radio = new Radio(['id'=>null,'codigo'=>$request->radios,'nombre'=>'Nuevo al segmentar: '.$request->radios]);
                Log::debug('No se encontró el radio: '.$request->radios.' Se
                crea temporalmente.');
            }
          if ($lucky!=true){
           $resultado = $radio->segmentar($localidad->codigo,
                                          $request['vivs_deseadas'],
                                          $request['vivs_max'],
                                          $request['vivs_min'],
                                          $request['mzas_indivisibles']);
          }else{
           $resultado = $radio->segmentarLucky($localidad->codigo,
                                          $request['vivs_deseadas'],
                                          $request['vivs_max'],
                                          $request['vivs_min'],
                                          $request['mzas_indivisibles']);
          }
            return  app('App\Http\Controllers\SegmentacionController')->ver($localidad,$radio);
        }else{
           flash('No selecciono ningún radio valido!');
           dd($request);
        }

    }

    public function ver_pxseg(Localidad $localidad)
    {
            $pxseg=MyDB::getPxSeg('e'.$localidad->codigo);
            $data = collect($pxseg); //json_encode ($pxseg);
            return view('segmentacion.pxseg',['pxseg'=>$data,'localidad'=>$localidad]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Localidad $localidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Localidad $localidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Localidad  $localidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Localidad $localidad)
    {
        //
    }
}
