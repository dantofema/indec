<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\MyDB;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:run-setup');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        MyDB::addUser('mretamozo');
        MyDB::addUser('halperin');
        MyDB::addUser('vheredia');
        MyDB::addUser('mretamozo','geoestadistica_test');


        MyDB::addUser('adibiase');
        MyDB::addUser('alitichever');
        MyDB::addUser('atoca');
        MyDB::addUser('efilgueira');
        MyDB::addUser('cdiaz');
        MyDB::addUser('fhaddad');
        MyDB::addUser('sfarace');
        MyDB::addUser('malves');
        MyDB::addUser('nallendes');
        MyDB::addUser('pdelsere');
        MyDB::addUser('sbouzas');
        MyDB::addUser('sfailde');
        MyDB::darPermisos('e0002');

        return view('home');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function permisos($schema)
    {
        MyDB::darPermisos($schema);
        return view('home');
    }


    public function grupoGeoestadistica($usuario)
    {
        MyDB::addUser($usuario);
        flash('Se agrego al grupo geoestadistica al usuario '.$usuario);
        return view('home');
    }

    public function grupoGeoestadisticaTabla($tabla)
    {
        MyDB::darPermisosTabla($tabla);
        return view('home');
    }

    public function cargarTopologia($schema)
    {
        MyDB::cargarTopologia($schema);
        flash('Se creo la topología para '.$schema);
        return view('home');
    }

    public function dropTopologia($schema)
    {
        MyDB::dropTopologia($schema);
        return view('home');
    }

    public function addIndexListado($schema)
    {
        MyDB::addIndexListado($schema);
        flash('Se creo el indice para lados en listado en '.$schema);
        MyDB::addIndexListadoId($schema);
        flash('Se creo el indice para id listado en '.$schema);
        MyDB::addIndexListadoRadio($schema);
        flash('Se creo el indice para radio en listado en '.$schema);
        return view('home');
    }

    public function addIndexId($tabla)
    {
        MyDB::addIndexId($tabla);
        flash('Se creo el indice para id en '.$tabla);
        return view('home');
    }

    public function createIndex($schema,$tabla,$cols)
    {
        MyDB::createIndex($schema,$tabla,$cols);
        flash('Se creo el indice para '.$tabla.' del esquema '.$schema.' para las columnas '.$cols);
        return view('home');
    }

    public function georeferenciarEsquema($schema)
    {
        MyDB::georeferenciar_listado($schema);
        flash('Se georeferencio el listado del esquema '.$schema);
        return view('home');
    }

    public function georeferenciarSegmentacionEsquema($schema)
    {
        // Georreferrenciar segmentación...
        MyDB::georeferenciar_segmentacion($schema);
        flash('Se georeferencio el listado del esquema '.$schema);
        return view('home');
    }

    public function segmentarEsquema($schema)
    {
        MyDB::segmentar_equilibrado($schema,36);
        flash('Se segmento el listado del esquema '.$schema);
        return view('home');
    }

    public function limpiarEsquema($schema)
    {
        MyDB::limpiar_esquema($schema);
        flash('Limpieza de esquema '.$schema);
	return view('home');
    }
    
    public function juntarSegmentos($schema)
    {
        flash('Resultado: '.MyDB::juntar_segmentos($schema));
        flash('Se juntaron los segmentos con 0 viviendas del esquema '.$schema);
        flash('Sincro R3: '.MyDB::grabarSegmentacion(substr($schema,1,strlen($schema)-1)));
        return view('home');
    }


    public function muestreaEsquema($schema)
    {
        $result= MyDB::muestrear($schema);
        flash('Se muestreo el esquema '.$schema.' 
        '.$result);
        return view('home');
    }

    public function limpiaEsquemasTemporales()
    {
        $result= MyDB::limpiaEsquemasTemporales();
        return view('home');
    }

    public function limpiaListado($schema)
    {
        MyDB::eliminaRepetidosListado($schema);
        MyDB::eliminaLSVconViviendasEnListado($schema);
        flash('Sincro R3: '.MyDB::grabarSegmentacion(substr($schema,1,strlen($schema)-1)));
        return view('home');
    }

    public function testFlash($texto='Mensaje de prueba.')
    {
        flash(' Normal  '.$texto);
        flash(' Error  '.$texto)->error();
        flash(' Info  '.$texto)->info();
        flash(' Success  '.$texto)->success();
        flash('Message success')->success();// Set the flash theme to "success".
        flash('Message error')->error();// Set the flash theme to "danger".
        flash('Message warning')->warning();// Set the flash theme to "warning".
        flash('Proyecto Mandarina Test Overlay flash message')->overlay();// Render the message as an overlay.
        flash()->overlay('Modal Message Mandarina', 'Mandarina Modal Title');// Display a modal overlay with a title.
        flash('Message important')->important();//: Add a close button to the flash message.
	flash('Message error()->important')->error()->important();//       
        flash(' Info Importante '.$texto)->info()->important();
        flash(' Success imporatnte  '.$texto)->success()->important();
        

        Log::emergency($texto);
        Log::alert($texto);
        Log::critical($texto);
        Log::error($texto);
        Log::warning($texto);
        Log::notice($texto);
        Log::info($texto);
        Log::debug($texto);       	
        return view('home');
    }
    
}
