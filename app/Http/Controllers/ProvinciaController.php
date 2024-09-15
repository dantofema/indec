<?php

namespace App\Http\Controllers;

use App\Model\Provincia;
use App\Model\Departamento;
use Illuminate\Http\Request;
use Redirect,Response,DB,Config;
use Datatables;
use Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
// $provincias = Provincia::withCount('departamentos')->orderBy('codigo','asc')->get()
//        $category = Departamento::find(3);
// dd($product);

// $provincia = $product->departamentos;
//dd($provincias);
     return view('provs');
    }


    public function provsList()
    {
           $aProvs=[];
           $provsQuery = Provincia::query();
           $codigo = (!empty($_GET["codigo"])) ? ($_GET["codigo"]) : ('');
           if ($codigo!='') {
              $provsQuery->where('codigo', '=', $codigo);
           }
    	  $provs = $provsQuery->select('*')
                ->withCount(['departamentos','fracciones'])
                ->with('departamentos')
                ->with('fracciones')
                ->with('fracciones.radios')
                ->with('fracciones.radios.tipo')
                ->with('departamentos.localidades')
                ->get('codigo','nombre')
                ->sort();
//        dd($provs->get());
        foreach ($provs as $prov){
          $prov->localidades_count=0;
          $prov->radios_count=0;
          $prov->radios_resultado_count=0;
          $prov->radios_count_u_m = 0;
//          $prov->fracciones_count=0;
//          $prov->fracciones_count = $prov->fracciones->count();
          $prov->radios_counts = [];
          foreach( $prov->fracciones as $fraccion ){
              $prov->radios_resultado_count += $fraccion->radios->whereNotNull('resultado')->count();
              $prov->radios_count += $fraccion->radios->count();
              $prov->radios_count_u_m += $fraccion->radios->whereIn('tipo_de_radio_id',[1,3])->count();
          }
          foreach( $prov->departamentos as $depto){
              $prov->localidades_count += $depto->localidades->filter(function ($localidad){
                        return substr($localidad->codigo,5,3) != '000';
                   })->count();
          }
          $aProvs[$prov->codigo]=['id'=>$prov->id,'codigo'=>$prov->codigo,'nombre'=>$prov->nombre,
                                  'localidades_count'=> $prov->localidades_count ,
                                  'radios_count'=>$prov->radios_count ,
                                  'radios_count_u_m'=>$prov->radios_count_u_m ,
                                  'radios_resumen'=>$prov->radios_counts ,
                                  'radios_resultado_count'=> $prov->radios_resultado_count ,
                                  'fracciones_count'=>$prov->fracciones_count,
                                  'departamentos_count'=>$prov->departamentos_count ];
        }
      return datatables()->of($aProvs)
                ->addColumn('action', function($data){
                    $button = '<button type="button" class="btn_descarga btn-sm btn-primary" > Descargar </button> ';
                    // botón de eliminar PROVINCIA  en test, si esta logueado.
                    if (Auth::check()) {
                            try {                 
                                $filtro = Permission::where('name',$data['codigo'])->first();                                
                                if ( $filtro and ( Auth::user()->hasPermissionTo($data['codigo'], 'filters') and Auth::user()->can('Borrar Provincia') and ($data['departamentos_count']==0) ) ) 
                                // Botón borrar sólo si tiene permiso y la provicnia no tiene deptos. (agregar or Auth::user()->hasRole('Super Admin') solo para tests)
                                {
                                    $button .= '<button type="button" class="btn_prov_delete btn-sm btn-danger "> Borrar </button>';
                                }
                            } catch (PermissionDoesNotExist $e) {
                            Log::warning('No existe el permiso "Borrar Provincia"');
                            }
                            return $button;
                        }
                })
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
     * @param  \App\Model\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function show(Provincia $provincia)
    {
        //
//	dd($provincia);
	return view('provview',['provincia' => $provincia->loadCount('departamentos')]);
    }

    public function show_post(Provincia $provincia)
    {
        //
	//return view('provinfo',['provincia' => Provincia::withCount('departamentos')->findOrFail($provincia)]);
	return view('provinfo',['provincia' => $provincia->loadCount('departamentos')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function edit(Provincia $provincia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provincia $provincia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Provincia  $provincia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provincia $provincia)
    {
        //
        //$provincia->middleware('auth');
        //$provincia->middleware('can:run-setup');

        //return $provincia->delete();

      $_info = $provincia->codigo.' '.$provincia->nombre;
      $deptos = count($provincia->departamentos);
      if ($deptos == 0){
        $filtro = Permission::where('name',$provincia->codigo)->first();
        if ($filtro && Auth::user()->hasPermissionTo($provincia->codigo, 'filters')) { 
            //agregar or Auth::user()->hasRole('Super Admin') solo para tests
            Log::info('El usuario posee el filtro: '.$provincia->codigo);
            if ($provincia->delete()) {
                Log::info('Se borró la provincia: '.$_info);
                $respuesta = ['statusCode'=> 200,'message' => 'Se eliminó la provincia: '.$_info];
            }else{
                    Log::error('NO se borró la provincia: '.$_info);
                    $respuesta = ['statusCode'=> 304,'message' => 'NO se eliminó la provincia: '.$_info];
            }
        } else {
            if ($filtro) {
                Log::error('El usuario no posee el filtro: '.$provincia->codigo);
            } else {
                Log::error('No existe el filtro: '.$provincia->codigo);
            }
            $respuesta = ['statusCode'=> 304,'message' => 'No cuenta con la autorización para eliminar la provincia: '.$_info];
        }
      } else {
          $respuesta = ['statusCode'=> 304,'message' => 'Existen '.$deptos.' departamentos que dependen de ésta provincia. '.$_info];
      }
      return response()->json($respuesta);

    }
}
