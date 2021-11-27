<?php

namespace App\Http\Controllers;

use App\Model\Archivo;
use Illuminate\Http\Request;
use Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use App\MyDB;
use App\Listado;
use App\Imports\CsvImport;
use Maatwebsite\Excel;
use App\Model\Aglomerado;
use App\Model\Provincia;
use App\Model\Departamento;
use App\Model\Localidad;
use App\Model\Radio;
use App\Model\Fraccion;
use App\Model\TipoRadio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SegmenterController extends Controller
{
    private $epsgs=[];

    public function __construct()
    {
        $segmenta_auto=false;
        $this->middleware('auth');
        $this->epsgs['epsg:22182']='(EPSG:22182) POSGAR 94/Argentina 2 - San Juan, Mendoza, Neuquén, Chubut, Santa Cruz y Tierra del Fuego...';
        $this->epsgs['epsg:22183']='(EPSG:22183) POSGAR 94/Argentina 3 - Jujuy, Salta, Tucuman, Catamárca, La Rioja, San Luis, La Pampa y Río Negro';
        $this->epsgs['epsg:22184']='(EPSG:22184) POSGAR 94/Argentina 4 - Santiago del Estero y Córdoba';
        $this->epsgs['epsg:22185']='(EPSG:22185) POSGAR 94/Argentina 5 - Formosa, Chaco, Santa Fe, Entre Ríos y Buenos Aires';
        $this->epsgs['epsg:22186']='(EPSG:22186) POSGAR 94/Argentina 6 - Corrientes';
        $this->epsgs['epsg:22187']='(EPSG:22187) POSGAR 94/Argentina 7 - Misiones';
        $this->epsgs['sr-org:8333']='(SR-ORG:8333) Gauss Krugger BA - Ciudad Autónoma de Buenos Aires';
    }

    public function index()
    {
    //	  $data['whoami'] = exec('whoami');
	    $data=null;
	    //dd(App()->make('App\Model\Radio'));
        return view('segmenter/index',['data' => $data,'epsgs'=> $this->epsgs]);
    }

    public function store(Request $request)
    {
    if (! Auth::check()) {
        $mensaje='No tiene permiso para segmentar o no esta logueado';
	flash($mensaje)->error()->important();
        return $mensaje;
    }else{
    $AppUser= Auth::user();
    $data = [];
    $segmenta_auto=false;
    $epsg_id = $request->input('epsg_id')?$request->input('epsg_id'):'epsg:22183';
    $data['epsg']['id']=$epsg_id;
    flash('SRS: '.$data['epsg']['id']);

    if ($request->hasFile('c1')) {
     if($c1_file = Archivo::cargar($request->c1, Auth::user())) {
         flash("Archivo C1 ")->info();
        } else {
         flash("Error en el modelo cargar archivo")->error();
     }
//        $random_name='t_'.$request->c1->hashName();
//        $data['file']['c1'] = $request->c1->storeAs('segmentador', $random_name); //.'.'.$request->c1->getClientOriginalExtension());
//        $original_extension = strtolower($request->c1->getClientOriginalExtension());
//        $original_name = $request->c1->getClientOriginalName();

        //Si no se cargo geometria tomo del nombre del listado  el nombre sin extension
        //caracteres del nombre, puede ser fecha
//        $codaglo=isset($codaglo)?$codaglo:substr($original_name,1,-3);

       $c1_file->procesar();
/*	     if ($original_extension == 'csv'){
		    $data['file']['csv_info'] = 'Se Cargo un csv.';
            $process = Process::fromShellCommandline('echo "C1 CSV: $name" >> archivos.log');
		    $process->run(null, ['name' => "Archivo: ".$original_name." subido como: ".$data['file']['c1']]);
    /*
		    $data['file']['csv_detail'] = Listado::cargar_csv( storage_path().'/app/'.$data['file']['c1']);
		     return view('listado/all', ['listado' => $data['file']['csv_detail'],'epsgs'=> $epsgs]);
    */
/*		    $import = new CsvImport;
		    Excel::import($import, storage_path().'/app/'.$data['file']['c1']);
	    //	dd('Row count: ' . $import->getRowCount()); 
		    return view('listado/all', ['listado' => $data['file']['csv_detail'],'epsgs'=> $epsgs]);

	      }elseif ($original_extension == 'dbf'){
            // Mensaje de subida de DBF y logeo en archivo.
            flash($data['file']['csv_info'] = 'Se Cargo un DBF.');
		    $processLog = Process::fromShellCommandline('echo "C1 DBF: $name" >> archivos.log');
		    $processLog->run(null, ['name' => "Archivo: ".$original_name." subido como: ".$data['file']['c1']]);

		    // Subo DBF con pgdbf a una tabla temporal.
            $process = Process::fromShellCommandline('pgdbf -s latin1 $c1_dbf_file | psql -h $host -p $port -U $user $db');
		    $process->run(null, ['c1_dbf_file' => storage_path().'/app/'.$data['file']['c1'],'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'port'=>Config::get('database.connections.pgsql.port'),'PGPASSWORD'=>Config::get('database.connections.pgsql.password')]);
        // executes after the command finishes
 */
     if (!$c1_file->procesado) {
            flash($data['file']['error']='Archivo sin Procesar por error')->important();
     }else{
            $codaglo=$c1_file->moverData();
/*            // Leo dentro del csv que aglo/s viene/n o localidad depto CABA

            $tabla = strtolower(
            substr($data['file']['c1'],strrpos($data['file']['c1'],'/')+1,-4) );
            $aglo_interno=MyDB::getAglo($tabla,'public');
            $codprov=MyDB::getProv($tabla,'public');
            if ($codprov=='02'){
                $ppdddlll=MyDB::getLoc($tabla,'public');
                flash($data['file']['caba']='Se detecto CABA: '.$ppdddlll);
                $codaglo=$ppdddlll;
                $segmenta_auto=true;
            }elseif ($codprov=='06'){
                $ppdddlll=MyDB::getLoc($tabla,'public');
                flash($data['file']['data']='Se detecto PBA: '.$ppdddlll);
                $codaglo=substr($ppdddlll,0,5);
            }else{
                $codaglo=$aglo_interno;
            }

		    MyDB::createSchema($codaglo);

            MyDB::moverDBF(storage_path().'/app/'.$data['file']['c1'],$codaglo);
            $data['file']['info_dbf']=MyDB::infoDBF('listado',$codaglo);
            flash('Subido:'.$data['file']['info_dbf'])->important();
//            $aglo= Aglomerado::where('codigo', $codaglo)->first();
            $data['file']['codigo_usado']=$codaglo;

       }
      
       }else{flash($data['file']['csv_info'] = 'Se Cargo un archivo de formato
       no esperado!')->error()->important();}
 */  }
    }
 
    if ($epsg_id=='sr-org:8333'){
            // Log::debug('Proyeccion de CABA en '.$codaglo.', con SRID: '.$epsg_id);
            // USO .prj 8333.prj
            $prj_file='./app/developer_docs/8333.prj';
            $epsg_def= $epsg_id;
            $epsg_def='+proj=tmerc +lat_0=-34.6297166 +lon_0=-58.4627 +k=1 +x_0=100000 +y_0=100000 +ellps=intl +units=m +no_defs';
	    $srs_name='sr-org:8333';
    }else {
            $epsg_def= '';
    }
        $codaglo=isset($codaglo)?$codaglo:'temporal';
            $processOGR2OGR =
                Process::fromShellCommandline('(/usr/bin/ogr2ogr -f \
                "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port \
                active_schema=e$e00 password=$pass" --config PG_USE_COPY YES \
                -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco \
                PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA \
                IF NOT EXISTS e$e00;" -dsco active_schema=e$e00 -lco \
                PRECISION=NO -lco SCHEMA=e$e00 \
                -nln $capa \
                -skipfailures \
                -overwrite $file )');
            $processOGR2OGR->setTimeout(3600);
    if ($request->hasFile('shp_lab')) {
            $original_name = $request->shp_lab->getClientOriginalName();
	    $original_extension = strtolower($request->shp_lab->getClientOriginalExtension());
        if ($original_extension == 'shp'){
            $random_name='t_'.$request->shp_lab->hashName();
            $data['file']['shp_lab'] = $request->shp_lab->storeAs('segmentador', $random_name.'.shp');
            if ($request->hasFile('shx_lab')) {
                $data['file']['shx_lab'] = $request->shx_lab->storeAs('segmentador', $random_name.'.shx');
            }
            if ($request->hasFile('prj_lab')) {
                $data['file']['prj_lab'] = $request->prj_lab->storeAs('segmentador', $random_name.'.prj');
            }
            if ($request->hasFile('dbf_lab')) {
                $data['file']['dbf_lab'] = $request->dbf_lab->storeAs('segmentador', $random_name.'.dbf');
	    }

	    //Cargo etiquetas
	    $processOGR2OGR->run(null, ['capa'=>'lab','epsg'=>$epsg_def,'file' => storage_path().'/app/'.$data['file']['shp_lab'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);

        }
    }
    if ($request->hasFile('shp')) {
     if($shp_file = Archivo::cargar($request->shp, Auth::user())) {
	     flash("Archivo Shp/E00 ")->info();
	     //$shp_file->descargar();
        } else {
         flash("Error en el modelo cargar archivo al procesar SHP/E00")->error();
     }
        if ($request->file('shp')->isValid() or true) {
            $data['file']['shp_msg'] = "Subió una base geográfica ";
            $original_name = $request->shp->getClientOriginalName();
            $data['file']['shp_msg'] .= " y nombre original: ".$original_name;
            $original_extension = strtolower($request->shp->getClientOriginalExtension());
            $data['file']['shp_msg'] .= ". Extension original: ".$original_extension;
	    flash($data['file']['shp_msg']);

        if ($original_extension == 'shp'){
            $random_name='t_'.$request->shp->hashName();
            $data['file']['shp'] = $request->shp->storeAs('segmentador', $random_name.'.'.$request->shp->getClientOriginalExtension());
            if ($request->hasFile('shx')) {
                $data['file']['shx'] = $request->shx->storeAs('segmentador', $random_name.'.'.$request->shx->getClientOriginalExtension());
            }
            if ($request->hasFile('prj')) {
                $data['file']['prj'] = $request->prj->storeAs('segmentador', $random_name.'.'.$request->prj->getClientOriginalExtension());
            }
            if ($request->hasFile('dbf')) {
                $data['file']['dbf'] = $request->dbf->storeAs('segmentador', $random_name.'.'.$request->dbf->getClientOriginalExtension());
            }

            $process = Process::fromShellCommandline('echo "$tiempo: $usuario_name ($usuario_id) -> $log" >> archivos.log');
            $process->run(null, ['log' => "Archivo: ".$original_name." subido como: ".$data['file']['shp'],
                                 'usuario_id' => $AppUser->id,
                                 'usuario_name' => $AppUser->name,
                                 'tiempo' => date('Y-m-d H:i:s')]);
                        
            $codaglo=isset($codaglo)?$codaglo:'test'; //$random_name;
            MyDB::createSchema($codaglo);

            if ($epsg_id=='sr-org:8333'){
		//Cargo arcos
		$processOGR2OGR->run(null, ['capa'=>'arc','epsg'=>$epsg_def,'file' => storage_path().'/app/'.$data['file']['shp'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);
            }else{
                $processOGR2OGR = Process::fromShellCommandline('/usr/bin/ogr2ogr -f "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port active_schema=e$e00 password=$pass" --config PG_USE_COPY YES -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA IF NOT EXISTS e$e00;" -dsco active_schema=e$e00 -lco PRECISION=NO -lco SCHEMA=e$e00 -s_srs $epsg -t_srs $epsg -nln arc -overwrite $file ');
                $processOGR2OGR->run(null, ['capa'=>'arc','epsg' => $epsg_id, 'file' => storage_path().'/app/'.$data['file']['shp'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);

                $processOGR2OGR->run(null, ['capa'=>'lab','epsg' => $epsg_id, 'file' => storage_path().'/app/'.$data['file']['shp_lab'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);
            }
            if (!$processOGR2OGR->isSuccessful()) {
                $epsg_def=isset($epsg_def)?$epsg_def:'No definido';
                dd($processOGR2OGR,'epsg '.$epsg_id,'epsg_def '.$epsg_def.
                'file '.storage_path().'/app/'.$data['file']['shp'],'e00 '.$codaglo);
                throw new ProcessFailedException($processOGR2OGR);
            }
            MyDB::agregarsegisegd($codaglo);

        }elseif ($original_extension == 'e00'){
            $random_name='t_'.$request->shp->hashName();
            $data['file']['shp'] = $request->shp->storeAs('segmentador', $random_name.'.'.$request->shp->getClientOriginalExtension());
            $process = Process::fromShellCommandline('echo "E00: $name" >> archivos.log');
            $process->run(null, ['name' => "Archivo: ".$original_name." subido como: ".$data['file']['shp']]);
            $processOGR = Process::fromShellCommandline('/usr/bin/ogrinfo -so $file ARC');
            $processOGR->run(null, ['file' => storage_path().'/app/'.$data['file']['shp']]);
            $data['file']['e00_info'] = $processOGR->getOutput();

            $original_name = substr($original_name,1,4);
            $codaglo=isset($codaglo)?$codaglo:$original_name;
            MyDB::createSchema($codaglo);

	    if ($epsg_id=='sr-org:8333'){ // Si es CABA cargo sin epsg
            $processOGR2OGR = Process::fromShellCommandline('/usr/bin/ogr2ogr -f "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port active_schema=e$e00 password=$pass port=$port" --config PG_USE_COPY YES -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA IF NOT EXISTS e$e00;" -dsco active_schema=e$e00 -lco PRECISION=NO -lco SCHEMA=e$e00 -skipfailures -addfields -overwrite $file ARC');
            $processOGR2OGR->setTimeout(3600);
            $processOGR2OGR->run(null, ['epsg' => $epsg_id, 'file' => storage_path().'/app/'.$data['file']['shp'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);
            //		dd($processOGR2OGR);	
            $processOGR2OGR_lab = Process::fromShellCommandline('/usr/bin/ogr2ogr -f "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port active_schema=e$e00 password=$pass" --config PG_USE_COPY YES -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA IF NOT EXISTS e$e00;" -dsco active_schema=e$e00 -lco PRECISION=NO -lco SCHEMA=e$e00 -skipfailures -addfields -overwrite $file LAB');
            $processOGR2OGR_lab->setTimeout(3600);
            $processOGR2OGR_lab->run(null, ['epsg' => $epsg_id, 'file' => storage_path().'/app/'.$data['file']['shp'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);
            //dd($processOGR2OGR_lab->getErrorOutput());
            flash($data['file']['ogr2ogr_lab'] = $processOGR2OGR_lab->getErrorOutput().'<br />'.$processOGR2OGR_lab->getOutput())->important();
	    flash($data['file']['ogr2ogr'] = $processOGR2OGR->getErrorOutput().'<br />'.$processOGR2OGR->getOutput())->important();
	    }else{ // Cargo con epsg
            $processOGR2OGR = Process::fromShellCommandline('/usr/bin/ogr2ogr -f "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port active_schema=e$e00 password=$pass port=$port" --config PG_USE_COPY YES -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA IF NOT EXISTS e$e00;" -dsco active_schema=e$e00 -lco PRECISION=NO -lco SCHEMA=e$e00 -s_srs $epsg -t_srs $epsg -skipfailures -addfields -overwrite $file ARC');
            $processOGR2OGR->setTimeout(3600);
            $processOGR2OGR->run(null, ['epsg' => $epsg_id, 'file' => storage_path().'/app/'.$data['file']['shp'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);
            //          dd($processOGR2OGR);    
            $processOGR2OGR_lab = Process::fromShellCommandline('/usr/bin/ogr2ogr -f "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port active_schema=e$e00 password=$pass" --config PG_USE_COPY YES -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA IF NOT EXISTS e$e00;" -dsco active_schema=e$e00 -lco PRECISION=NO -lco SCHEMA=e$e00 -s_srs $epsg -t_srs $epsg -skipfailures -addfields -overwrite $file LAB');
            $processOGR2OGR_lab->setTimeout(3600);
            $processOGR2OGR_lab->run(null, ['epsg' => $epsg_id, 'file' => storage_path().'/app/'.$data['file']['shp'],'e00'=>$codaglo,'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'pass'=>Config::get('database.connections.pgsql.password'),'port'=>Config::get('database.connections.pgsql.port')]);
	    //dd($processOGR2OGR_lab->getErrorOutput());
	    //
            $mensaje=$data['file']['ogr2ogr_lab'] = $processOGR2OGR_lab->getErrorOutput().'<br />'.$processOGR2OGR_lab->getOutput();
	    if (!Str::contains($mensaje,['ERROR'])){
	    	flash('Se cargaron las Etiquetas con éxito. ')->important()->success();
	    }else{
	    	flash($mensaje)->important()->error();
	    }
            $mensaje=$data['file']['ogr2ogr'] = $processOGR2OGR->getErrorOutput().'<br />'.$processOGR2OGR->getOutput();
	    if (!Str::contains($mensaje,['ERROR'])){
	    	flash('Se cargaron los Arcos con éxito. ')->important()->success();
	    }else{
	    	flash($mensaje)->important()->error();
	    }

	    }

            MyDB::agregarsegisegd($codaglo);
        }else {//dd($request->file('shp')); 
            flash('File geo not valid')->error()->important();
        }
        if (isset($codaglo)){
            if ($epsg_id=='sr-org:8333'){
	        MyDB::setSRID('e'.$codaglo,98333);
	    }
            MyDB::juntaListadoGeom('e'.$codaglo);
            if($segmenta_auto) {
                    MyDB::segmentar_equilibrado($codaglo,36);
                    flash('Segmentado automáticamente a 36 viviendas x segmento')->important();
                    flash('Resultado: '.MyDB::juntar_segmentos('e'.$codaglo));
            }
	 }
      }
    }
    if ($request->hasFile('pxrad')) {
     if($pxrad_file = Archivo::cargar($request->pxrad, Auth::user())) {
         flash("Archivo PxRad ")->info();
        } else {
         flash("Error en el modelo cargar archivo")->error();
     }
        $random_name='t_'.$request->pxrad->hashName();
        $data['file_pxrad']['pxrad'] = $request->pxrad->storeAs('segmentador', $random_name); //.'.'.$request->c1->getClientOriginalExtension());
        $original_extension = strtolower($request->pxrad->getClientOriginalExtension());
        $original_name = $request->pxrad->getClientOriginalName();

        //Si no se cargo geometria tomo del nombre del listado los utilmos 8
        //caracteres del nombre, puede ser fecha
        $codaglo=isset($codaglo)?$codaglo:substr($original_name,-13,9);

	     if ($original_extension == 'csv'){
		$data['file_pxrad']['csv_info'] = 'Se Cargo un csv en pxrad, no esperado.';
	     }
	     elseif ($original_extension == 'dbf'){
		$data['file_pxrad']['dbf_info'] = 'Se Cargo una pxrad en formato dbf.';
		// Subo DBF con pgdbf a una tabla temporal.
            	$process = Process::fromShellCommandline('pgdbf -s UTF8 $pxrad_dbf_file | psql -h $host -p $port -U $user $db');
		$process->run(null, ['pxrad_dbf_file' => storage_path().'/app/'.$data['file_pxrad']['pxrad'],'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'port'=>Config::get('database.connections.pgsql.port'),'PGPASSWORD'=>Config::get('database.connections.pgsql.password')]);
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            flash($data['file']['error']=$process->getErrorOutput())->important();
	}else{
            $tabla = strtolower(
	    substr($data['file_pxrad']['pxrad'],strrpos($data['file_pxrad']['pxrad'],'/')+1,-4) );    
	    if (! Schema::hasTable($tabla)){
	        $process = Process::fromShellCommandline('pgdbf -s latin1 $pxrad_dbf_file | psql -h $host -p $port -U $user $db');
	        $process->run(null, ['pxrad_dbf_file' => storage_path().'/app/'.$data['file_pxrad']['pxrad'],'db'=>Config::get('database.connections.pgsql.database'),'host'=>Config::get('database.connections.pgsql.host'),'user'=>Config::get('database.connections.pgsql.username'),'port'=>Config::get('database.connections.pgsql.port'),'PGPASSWORD'=>Config::get('database.connections.pgsql.password')]);
	        flash('La PxRad dbf fue procesada como latin1')->warning()->important();
            }
	    // Leo dentro de la tabla importada desde el dbf 
	    flash($data['file_pxrad']['info']= 'Resultado carga dbf: '.$process->getOutput())->success()->important();
            
	    try{
            $procesar_result=MyDB::procesarPxRad($tabla,'public');
	    // Busco provincia encontrada en pxrad:
	    //
	    //
	    $prov=MyDB::getCodProv($tabla,'public');
	    if($prov==0){
		    flash('Error grave. Buscando provincia. NO SE PUDO PROCESAR PXRAD ? ')->error()->important();
		    $data['file']['pxrad']='none';
                    return view('segmenter/index', ['data' => $data,'epsgs'=> $this->epsgs]);
	    }
	    $oProvincia= Provincia::where('codigo', $prov)->first();
	    if ($oProvincia==null){
	    	$prov_data=MyDB::getDataProv($tabla,'public');
		$oProvincia= new Provincia ($prov_data);
		if ($oProvincia->save()){
			flash('Se creó la provincia: '.$oProvincia->tojson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))->success()->important();
		}

	    }
	    }catch (Illuminate\Database\QueryException $e){
		    flash('Error grave. NO SE PUDO PROCESAR PXRAD '.$e)->error()->important();
		    $data['file']['pxrad']='none';
                    return view('segmenter/index', ['data' => $data,'epsgs'=> $this->epsgs]);
	    }
//	    Log::debug('Provincia: '.$oProvincia->tojson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	    
	    $depto_data=MyDB::getDatadepto($tabla,'public');
//	    Log::debug('Deptos data: '.collect($depto_data) ); //.' cantidad: '.count($depto_data));
	    foreach($depto_data as $depto){
		    $depto->provincia_id=$oProvincia->id;
		    //:dd($depto);
		    $oProvincia->Departamentos()->save($oDepto = Departamento::firstOrCreate(['codigo'=>$depto->codigo
		    ],collect($depto)->toArray()));
		    Log::debug('Depto: '.$oDepto->tojson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                    // Recorro Fracciones leídas del departamento
		    $frac_data=MyDB::getDataFrac($tabla,'public',$oDepto->codigo);
		    foreach($frac_data as $fraccion){
		        $oDepto->Fracciones()->save($oFraccion = Fraccion::firstOrCreate(['codigo'=>$fraccion->codigo
                        ],collect($fraccion)->toArray()),false);
		    }

		    //Leo Localidades y recorro
		    $loc_data=MyDB::getDataLoc($tabla,'public',$oDepto->codigo);
		    foreach($loc_data as $localidad){
//		        dd($oDepto,$localidad,Localidad::firstOrNew(collect($localidad)->toArray()));
		        $localidad->depto_id=$oDepto->id;
		        $oDepto->load('localidades');
		        $oDepto->Localidades()->sync($oLocalidad = Localidad::firstOrCreate(['codigo'=>$localidad->codigo
			],collect($localidad)->toArray()),false);
			$estado=$oLocalidad->wasRecentlyCreated?' (nueva) ':' (guardada) ';
	                Log::debug('Localidad: '.$estado.$oLocalidad->tojson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
//			$aLocalidades[]=Localidad::firstOrNew(collect($localidad)->toArray());

			// Busco Aglomerado de la localidad y asigno localidad al aglomerado
			$aglo_data=MyDB::getDataAglo($tabla,'public',$oLocalidad->codigo);
			$oLocalidad->Aglomerado()->associate(Aglomerado::firstorCreate(['codigo'=>$aglo_data->codigo],
				collect($aglo_data)->toArray()));
			$oLocalidad->save();

                        // Obtengo, recorro y cargo los radios
	                $radio_data=MyDB::getDataRadio($tabla,'public',$oLocalidad->codigo);
			foreach($radio_data as $radio){
		            $radio->localidad_id=$oLocalidad->id;
                            $oLocalidad->load('radios');
                            $oLocalidad->Radios()->sync($oRadio = Radio::firstOrCreate(['codigo'=>$radio->codigo
			    ],collect($radio)->toArray()),false);
				
			    $estado=$oRadio->wasRecentlyCreated?' (nueva) ':' (guardada) ';
			    $oRadio->Fraccion()->associate(Fraccion::where('codigo',substr($radio->codigo,0,7))->firstorFail());
			    $oRadio->Tipo()->associate(TipoRadio::firstOrCreate(['nombre'=>$radio->tipo]));
                            $oRadio->save();

                            Log::debug('Radio: '.$estado.$oRadio->tojson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                        }
		    }
	    }
	    
	    Log::debug('Provincia: '.$oProvincia->tojson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

	}

	     }
	    $data['file']['pxrad']='Si';
    }else{
	    $data['file_pxrad']['pxrad']='none pxrad';
	    $data['file']['pxrad']='none';
    }

	    if(isset($oDepto)){
		    //return redirect('/depto/'.$oDepto->id);
		    return view('deptoview',['departamento' =>
                           $oDepto->loadCount('localidades')]);
	    }else{
	        return view('segmenter/index', ['data' => $data,'epsgs'=> $this->epsgs]);
	    }
    }
  }
}
