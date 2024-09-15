<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\RuntimeException;
use Illuminate\Support\Facades\Config;
use App\Imports\SegmentosImport;
use App\Imports\CsvImport;
use Maatwebsite\Excel\Facades\Excel;
use App\MyDB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Exceptions\GeoestadisticaException;
use App\User;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class Archivo extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
            'user_id', 'nombre_original', 'nombre', 'tipo', 'checksum', 'size', 'mime', 'tabla'
    ];
    protected $attributes = [
        'procesado' => false,
        'epsg_def' => 'epsg:22195'
    ];

    //Relación con usuario que subió el archivo.
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function viewers()
    {
       return $this->belongsToMany(User::class, 'file_viewer');
    }

    public function checksum_control()
    {
        return $this->hasOne(ChecksumControl::class);
    }

    // Funciona para checksum shape (varios files)
    private static function checksumCalculate($request_file, $shape_files = []){
        // O generar archivo comprimido de una vez :/
        // DO IT
        $checksum = md5_file($request_file); //->getRealPath());
        if ($shape_files != null){
            $checksums[] = $checksum;
            foreach ($shape_files as $key => $shape_file) {
                // Hay e00 con shapefiles con valor null
                if ($shape_file != null and $shape_file != ''){
                  $checksums[] =  md5_file($shape_file);//->getRealPath());
                }
            }
            $checksum = md5(implode('',$checksums));
        }
        return $checksum;
    }

    //recalcula el checksum según corresponda para archivos con checksums obsoletos
    public function checksumRecalculate(){
        if ($this->isMultiArchivo()){
            // si soy multiarchivo calculo el checksum en base a mis shapefiles
            $shape_files = $this->getArchivosSHP();
            $shp = array_shift($shape_files);
            $checksum = $this->checksumCalculate($shp, $shape_files);
        } else {
            $checksum = md5(Storage::get($this->nombre));
        }
        // guardo el nuevo checksum en el archivo
        $this->checksum = $checksum;
        $this->save();

        // guardo el checksum en su checksum_control
        $control = $this->checksum_control;
        if (!$control) {
            // si no existe lo creo
            $control = new ChecksumControl();
            $control->checksum = $checksum;
            $this->checksum_control()->save($control);
        } else {
            $control->checksum = $checksum;
            $control->save();
        }
    }

    // isMultiArchivo, si es del tipo que son muchos archivos.
    public function ismultiArchivo() {
      if ( in_array($this->tipo,['shp','shp/lab']) ) {
        return true;
      } else {
        return false;
      }
    }

    // Funciona para verificar que es checksum del archivo esté actualizado
    public function getcheckChecksumAttribute(){
        $result = true;
        $control = $this->checksum_control;
        if($control) {
            if ($this->checksum != $control->checksum) {
                $result = false;
                Log::error($this->nombre_original.' checksum mal calculado!');
            } else {
                Log::info($this->nombre_original.' checksum ok!');
            }
        } else {
            Log::warning($this->nombre_original.' no tiene el checksum calculado!');
        }
        return $result;
    }

    public function checkStorage(){
        $result = true;
        if ( $this->isMultiArchivo() ){
            $nombre = explode(".",$this->nombre)[0];
            $nombre_original = explode(".",$this->nombre_original)[0];
            // busco para cada extension
            $extensiones = [".dbf", ".shx", ".prj"];
            foreach ($extensiones as $extension) {
                $n = $nombre . $extension;
                if(Storage::exists($n)) {
                    Log::info($nombre_original.$extension.' Storage ok!');
                } else {
                    Log::error($nombre_original.$extension.' Storage missing! ('.$n.')');
                    $result = false;
                }
            }
        } else {
            $n = $this->nombre;
            if (Storage::exists($n)){
                Log::info($this->nombre_original.' Storage ok!');
            } else {
                Log::error($this->nombre_original.' Storage missing!('.$n.')');
                $result = false;
            }
        }
       return $result;
    }

    // Funcion para cargar información de archivo en la base de datos.
    public static function cargar($request_file, $user, $tipo=null, $shape_files = []) {
        $original_extension = strtolower($request_file->getClientOriginalExtension());
        $checksum = self::checksumCalculate($request_file, $shape_files);
        # Ordeno por id para que, de ser necesario, el registro en file_viewer se cree sobre el archivo original y no una copia
        $file = self::where('checksum', '=', $checksum)->orderBy('id', 'asc')->first();
        if (!$file) {
            flash("Nuevo archivo ".$original_extension);
            $guess_extension = strtolower($request_file->guessClientExtension());
            $original_name = $request_file->getClientOriginalName();
            $random_name= 't_'.$request_file->hashName();
            $random_name = substr($random_name,0,strpos($random_name,'.'));
            $file_storage = $request_file->storeAs('segmentador', $random_name.'.'.$request_file->getClientOriginalExtension());
            $size_total = $request_file->getSize();
            if ($tipo == 'shape'){
                if ($shape_files != null){
                    $data_files[] = null;
                    foreach ($shape_files as $shape_file) {
                        //Almacenar archivos asociados a shapefile con igual nombre
                        //según extensión.
                        if ($shape_file != null and $shape_file != ''){
                            $extension = strtolower($shape_file->getClientOriginalExtension());
                            $data_files[] = $shape_file->storeAs('segmentador', $random_name.'.'.$extension);
                            $size_total = $size_total + $shape_file->getSize();
                        };
                    }
                }
            }

            $file = self::create([
                'user_id' => $user->id,
                'nombre_original' => $original_name,
                'nombre' => $file_storage,
                'tabla' => $random_name,
                'tipo' => ($guess_extension!='bin' and $guess_extension!='')?$guess_extension:$original_extension,
                'checksum'=> $checksum,
                'size' => $size_total,
                'mime' => $request_file->getClientMimeType()
            ]);
            // guardo el checksum en su checksum_control
            $control = $file->checksum_control;
            if (!$control) {
                // si no existe lo creo
                $control = new ChecksumControl();
                $control->checksum = $checksum;
                $file->checksum_control()->save($control);
            } else {
                $control->checksum = $checksum;
                $control->save();
            }
        } else {
            # Si no es su archivo y no está en sus archivos visibles lo agrego
            if (!$user->visible_files()->get()->contains($file) and !$user->mis_files()->get()->contains($file)){
                $user->visible_files()->attach($file->id);
            }
            flash("Archivo ".$original_extension." ya existe. Cargado el  ".$file->created_at->format('Y-m-d').' por '.$file->user->name)->info();
        }
        return $file;
    }

    // Descarga del archivo cargado con nombre: mandarina_ + time() + _nombre_original
    // TODO: ver shape de descargar conjunto de archivos.
    public function descargar() {
        flash('Descargando... '.$this->nombre_original);

        if ( $this->isMultiArchivo() ) {
            return $this->downloadZip();
        }
        $file = storage_path().'/app/'.$this->nombre;
        $name = 'mandarina_'.time().'_'.$this->nombre_original;
        $headers = ['Content-Type: '.$this->mime];
        return response()->download($file, $name, $headers);
    }

    public function downloadZip()
    {
        $zip = new ZipArchive;

        $fileName = 'mandarina_'.time().'_'.$this->nombre_original.'.zip';

        if ($zip->open(storage_path().'/app/'.$fileName, ZipArchive::CREATE) === TRUE) {

            $files = $this->getArchivosSHP();

            foreach ($files as $key => $value) {
                try {
                  $relativeNameInZipFile = $key;
                  $zip->addFile($value, $relativeNameInZipFile);
                } catch (ErrorException $e) {
                  Log::error('No se encontreo el archivo:'.$e->getMessage());
                  dd($value);
                }
            }

            $zip->close();
        }

        return response()->download(storage_path().'/app/'.$fileName);
    }

    public function getArchivosSHP() {
        $nombre = substr($this->nombre,0,-4);
        $nombre_original = substr($this->nombre_original,0,-4);

        // genero nombre para cada extension
        $extensiones = [".shp", ".shx", ".dbf", ".prj"];
        foreach ($extensiones as $extension) {
            $o = storage_path().'/app/'.$nombre . $extension;
            $key = 'mandarina_'.$nombre_original . $extension;
            $archivos[$key]= $o;
        }
        return $archivos;
    }


    public function procesar(bool $force = true)
    {
        if (!$this->procesado or $force) {
            if ($this->tipo == 'csv' or $this->tipo == 'dbf') {
                if (( strtolower(substr($this->nombre_original, 0, 8)) == 'tablaseg')
                    or ( strtolower(substr($this->nombre_original, 0, 7))    == 'segpais')
                    or ( strtolower(substr($this->nombre_original, 0, 21))    == 'tabla_de_segmentacion')
                    or ( strtolower(substr($this->nombre_original, 0, 14))    == 'segmento_total')
                ) {
                    return $this->procesarSegmentos();
                } elseif ($this->tipo == 'csv') {
                    return $this->procesarViviendas();
                  }
                  else {
                    return $this->procesarDBF();
                }
            } elseif ($this->tipo == 'e00' or $this->tipo == 'bin') {
                return $this->procesarGeomE00();
            } elseif ($this->tipo == 'pxrad/dbf') {
                return $this->procesarPxRad();
            } elseif ($this->tipo == 'shp') {
                if ( strtolower(substr($this->nombre_original, 0, 11)) == 'Localidades') {
                  return $this->procesarGeomSHP('localidades');
                }
                return $this->procesarGeomSHP();
            } elseif ($this->tipo == 'shp/arc') {
                return $this->procesarGeomSHP();
            } elseif ($this->tipo == 'shp/lab') {
                return $this->procesarGeomSHP('lab');
            } elseif ($this->tipo == 'shp/pol') {
                return $this->procesarGeomSHP('pol');
            } else {
                flash('No se encontro qué hacer para procesar '.$this->nombre_original.'. tipo = '.$this->tipo)->warning();
                return false;
            }
        } else {
            flash('Archivo ya fue procesado: '.$this->nombre_original)->warning();
            return true;
        }
    }

    public function procesarDBF()
    {
        if ($this->tipo == 'csv'){
            $mensaje = 'Se Cargo un csv.';
            $import = new CsvImport;
            $import->delimiter = "|";
            Excel::import($import, storage_path().'/app/'.$this->nombre);
            $this->procesado=true;
            $this->save();
            return true;
        } elseif ($this->tipo == 'dbf' or $this->tipo='pxrad/dbf' ){
            // Mensaje de subida de DBF.
            flash('Procesando DBF.')->info();

            // Subo DBF con pgdbf a una tabla temporal.
            $process = Process::fromShellCommandline('pgdbf -s $encoding $dbf_file | psql -h $host -p $port -U $user $db');
            try {
                $process->run(null, [
                    'encoding'=>'latin1',
                    'dbf_file' => storage_path().'/app/'.$this->nombre,
                    'db'=>Config::get('database.connections.pgsql.database'),
                    'host'=>Config::get('database.connections.pgsql.host'),
                    'user'=>Config::get('database.connections.pgsql.username'),
                    'port'=>Config::get('database.connections.pgsql.port'),
                    'PGPASSWORD'=>Config::get('database.connections.pgsql.password')]
                );
                //    $process->mustRun();
          // executes after the command finishes
          if (Str::contains($process->getErrorOutput(),['ERROR'])){
              $process->run(null, [
                    'encoding'=>'utf8',
                    'dbf_file' => storage_path().'/app/'.$this->nombre,
                    'db'=>Config::get('database.connections.pgsql.database'),
                    'host'=>Config::get('database.connections.pgsql.host'),
                    'user'=>Config::get('database.connections.pgsql.username'),
                    'port'=>Config::get('database.connections.pgsql.port'),
                    'PGPASSWORD'=>Config::get('database.connections.pgsql.password')]);
              Log::warning('Error cargando DBF.',[$process->getOutput(),$process->getErrorOutput()]);
              if (Str::contains($process->getErrorOutput(),['ERROR'])){
                  Log::error('Error cargando DBF.',[$process->getOutput(),$process->getErrorOutput()]);
                  flash('Error cargando DBF. '.$process->getErrorOutput())->important()->error();
                  return false;
              }
          } else {
            $this->procesado=true;
            $this->save();
            Log::debug($process->getOutput().$process->getErrorOutput());
            return true;
          }
      } catch (ProcessFailedException $exception) {
          Log::error($process->getErrorOutput().$exception);
      } catch (RuntimeException $exception) {
          Log::error($process->getErrorOutput().$exception);
      }
    } else {
    flash($data['file']['csv_info'] = 'Se Cargo un archivo de formato
         no esperado!')->error()->important();
          $this->procesado=false;
          return false;
        }
    }

    public function procesarGeomSHP($capa = 'arc') {
        MyDB::createSchema('_'.$this->tabla);
        flash('Procesando Geom desde Shape '.$capa.'...')->warning();
        $mensajes = '';
        $path_ogr2ogr = "/usr/bin/ogr2ogr";
        $processOGR2OGR = Process::fromShellCommandline(
            '( $ogr2ogr -f \
            "PostgreSQL" PG:"dbname=$db host=$host user=$user port=$port \
            active_schema=e$esquema password=$pass" --config PG_USE_COPY YES \
            -lco OVERWRITE=YES --config OGR_TRUNCATE YES -dsco \
            PRELUDE_STATEMENTS="SET client_encoding TO latin1;CREATE SCHEMA \
            IF NOT EXISTS e$esquema;" -dsco active_schema=e$esquema -lco \
            PRECISION=NO -lco SCHEMA=e$esquema \
            -nln $capa \
            -skipfailures \
            -overwrite $file )'
        );
        $processOGR2OGR->setTimeout(1800);

        //Cargo etiquetas
        try{
            $processOGR2OGR->run(null,[
                'capa'=>$capa,
                'epsg'=> $this->epsg_def,
                'file' => storage_path().'/app/'.$this->nombre,
                'esquema'=>'_'.$this->tabla,
                'encoding'=>'cp1252',
                'db'=>Config::get('database.connections.pgsql.database'),
                'host'=>Config::get('database.connections.pgsql.host'),
                'user'=>Config::get('database.connections.pgsql.username'),
                'pass'=>Config::get('database.connections.pgsql.password'),
                'port'=>Config::get('database.connections.pgsql.port'),
                'ogr2ogr'=>$path_ogr2ogr
            ]);
            $mensajes.='<br />'.$processOGR2OGR->getErrorOutput().'<br />'.$processOGR2OGR->getOutput();
            flash($mensajes)->info();
            $this->procesado=true;
        } catch (ProcessFailedException $exception) {
            Log::error($processOGR2OGR->getErrorOutput());
            flash('Error Importando Shape '.$this->nombre_original)->info();
            $this->procesado=false;
        } catch (RuntimeException $exception) {
            Log::error($processOGR2OGR-->getErrorOutput().$exception);
            flash('Error Importando Runtime Shape '.$this->nombre_original)->info();
            $this->procesado=false;
        } catch(ProcessTimedOutException $exception){
            Log::error($processOGR2OGR->getErrorOutput().$exception);
            flash('Se agotó el tiempo Importando Shape de... etiquetas '.$this->nombre_original)->info();
            $this->procesado=false;
        }
        $this->save();
        return $this->procesado;
    }

    public function procesarGeomE00() {
        flash('Procesando Arcos y Etiquetas (Importando E00.) ')->info();
        MyDB::createSchema('_'.$this->tabla);
        $processOGR2OGR = Process::fromShellCommandline(
            '/usr/bin/ogr2ogr -f "PostgreSQL" \
            PG:"dbname=$db host=$host user=$user port=$port active_schema=e_$esquema \
            password=$pass" --config PG_USE_COPY YES -lco OVERWRITE=YES \
            --config OGR_TRUNCATE YES -dsco PRELUDE_STATEMENTS="SET client_encoding TO $encoding; \
            CREATE SCHEMA IF NOT EXISTS e_$esquema;" -dsco active_schema=e_$esquema \
            -lco PRECISION=NO -lco SCHEMA=e_$esquema -s_srs $epsg -t_srs $epsg \
            -nln $capa -addfields -overwrite $file $capa'
        );
        $processOGR2OGR->setTimeout(1800);
        // -skipfailures
        //Cargo arcos
        try {
            $processOGR2OGR->run(null,[
                'capa'=>'arc',
                'epsg'=> $this->epsg_def,
                'file' => storage_path().'/app/'.$this->nombre,
                'esquema'=>$this->tabla,
                'encoding'=>'cp1252',
                'db'=>Config::get('database.connections.pgsql.database'),
                'host'=>Config::get('database.connections.pgsql.host'),
                'user'=>Config::get('database.connections.pgsql.username'),
                'pass'=>Config::get('database.connections.pgsql.password'),
                'port'=>Config::get('database.connections.pgsql.port')
            ]);
            $mensajes=$processOGR2OGR->getErrorOutput().'<br />'.$processOGR2OGR->getOutput();
        } catch (ProcessFailedException $exception) {
            Log::error($processOGR2OGR->getErrorOutput());
            flash('Error Importando Falló E00 '.$this->nombre_original)->info();
            return false;
        } catch (RuntimeException $exception) {
            Log::error($processOGR2OGR->getErrorOutput().$exception);
            flash('Error Importando Runtime E00 '.$this->nombre_original)->info();
            return false;
        } catch(ProcessTimedOutException $exception){
            Log::error($processOGR2OGR->getErrorOutput().$exception);
            flash('Se agotó el tiempo Importando E00 de arcos '.$this->nombre_original)->info();
            return false;
        }
        //Cargo etiquetas
        try{
            $processOGR2OGR->run(null,[
                'capa'=>'lab',
                'epsg'=> $this->epsg_def,
                'file' => storage_path().'/app/'.$this->nombre,
                'esquema'=>$this->tabla,
                'encoding'=>'cp1252',
                'db'=>Config::get('database.connections.pgsql.database'),
                'host'=>Config::get('database.connections.pgsql.host'),
                'user'=>Config::get('database.connections.pgsql.username'),
                'pass'=>Config::get('database.connections.pgsql.password'),
                'port'=>Config::get('database.connections.pgsql.port')
            ]);
            $mensajes.='<br />'.$processOGR2OGR->getErrorOutput().'<br />'.$processOGR2OGR->getOutput();
            $this->procesado=true;
        } catch (ProcessFailedException $exception) {
            Log::error($processOGR2OGR->getErrorOutput());
            flash('Error Importando Falló E00 '.$this->nombre_original)->info();
            $this->procesado=false;
            return false;
        } catch (RuntimeException $exception) {
            Log::error($processOGR2OGR-->getErrorOutput().$exception);
            flash('Error Importando Runtime E00 '.$this->nombre_original)->info();
            $this->procesado=false;
            return false;
        } catch(ProcessTimedOutException $exception){
            Log::error($processOGR2OGR->getErrorOutput().$exception);
            flash('Se agotó el tiempo Importando E00 de etiquetas '.$this->nombre_original)->info();
            return false;
        }
        $this->save();
        return $mensajes;
    }

    // Copia o Mueve listados de una C1 al esquema de las localidades encontradas
    // Retorna Array $ppdddlls con codigos de localidades
    public function moverData() {
        // Busca dentro de la tabla las localidades
        $ppdddllls = MyDB::getLocs($this->tabla,'public');
        $count = 0;
        foreach ($ppdddllls as $ppdddlll) {
            flash('Se encontró loc en C1: '.$ppdddlll->link);
            MyDB::createSchema($ppdddlll->link);
            if (substr($ppdddlll->link, 0, 2) == '02') {
                flash($data['file']['caba']='Se detecto CABA: '.$ppdddlll->link);
                $codigo_esquema=$ppdddlll->link;
                $segmenta_auto=true;
            } elseif (substr($ppdddlll->link, 0, 2) == '06') {
                flash($data['file']['data']='Se detecto PBA: '.$ppdddlll->link);
                //$codigo_esquema=substr($ppdddlll->link, 0, 5);
                // Se utiliza el código de localidad también para PBA
                $codigo_esquema=$ppdddlll->link;
            } else {
                $codigo_esquema=$ppdddlll->link;
            }
            MyDB::moverDBF(storage_path().'/app/'.$this->nombre,$codigo_esquema,$ppdddlll->link);
            $count++;
        }
        Log::debug('C1 se copió en '.$count.' esquemas');
        MyDB::borrarTabla($this->tabla);
        return $ppdddllls;
    }

    // Pasa data geo, arcos y labels al esquema de las localidades encontradas
    // Retorna Array $ppdddlls con codigos de localidades
    // TODO: Separar o manejar arc or lab x separado
    public function pasarData(){
        // Leo dentro de la tabla de etiquetas la/s localidades
        $ppdddllls=MyDB::getLocs('lab','e_'.$this->tabla);
        $count=0;
        // Si no encuentro localidades en lab.
        if ($ppdddllls==[]) {
            // Intento cargar pais x depto :D
            $coddeptos = MyDB::getDptos('lab', 'e_'.$this->tabla);
            $coddeptos_pol = MyDB::getDptos('arc', 'e_'.$this->tabla);
            $codprov = MyDB::getProv('lab', 'e_'.$this->tabla);
            $codprov_pol = MyDB::getProv('arc', 'e_'.$this->tabla);

            flash('Puede ser una "pais" x prov con deptos: '.count($coddeptos).' o '.count($coddeptos_pol));

            if ($codprov != null){
                flash('Se encontró Provincia : '.$codprov);
//                MyDB::createSchema($coddepto->link);
//                MyDB::copiaraEsquemaPais('e_'.$this->tabla,'e'.$coddepto->link,$coddepto->link);
                MyDB::createSchema($codprov);
                MyDB::copiaraEsquemaPais('e_'.$this->tabla,'e'.$codprov,'lab',null,$codprov);
                $count++;
            }

            if ($codprov_pol != null){
                flash('Se encontró Departamentos en arc/pol : '.$codprov_pol);
//                MyDB::createSchema($coddepto->link);
//                MyDB::copiaraEsquemaPais('e_'.$this->tabla,'e'.$coddepto->link,$coddepto->link);
                MyDB::createSchema($codprov_pol);
                MyDB::copiaraEsquemaPais('e_'.$this->tabla,'e'.$codprov_pol,'arc',null,$codprov_pol);
                $count++;
                $codprov=$codprov_pol;
            }

            MyDB::limpiar_esquema('e_'.$this->tabla);
            $prov=array('link'=>$codprov);
            $resultado[0] = (object) $prov;
            return $resultado;
        } else {
            // Para cada localidad encontrada
            // creo esquema y copio datos a esquema según codigo.
            foreach ($ppdddllls as $ppdddlll) {
                flash('Se encontró loc Etiquetas: '.$ppdddlll->link);
                MyDB::createSchema($ppdddlll->link);
                //MyDB::moverEsquema('e_'.$this->tabla,'e'.$ppdddlll->link);
                MyDB::copiaraEsquema('e_'.$this->tabla,'e'.$ppdddlll->link,$ppdddlll->link);
                $count++;
            }
            flash('Se encontraron '.$count.' localidaes en la cartografía');
            MyDB::limpiar_esquema('e_'.$this->tabla);
            return $ppdddllls;
        }
    }

    public function infoData(){
         return MyDB::infoDBF('listado',$this->tabla);
    }

    // Archivos csv con tabla de segmentación generada en modo manual.
    // x provincia juntando /y corrigiendo) segmentación Urbana y Urbano-Mixta con
    // segmentos Rural, Rural-Mixto.
    public function procesarSegmentos() {
        $mensaje = 'Se procesa un csv. Segmentos completos? Resultado: ';
        $import = new SegmentosImport($this->user);
        Excel::import($import, storage_path().'/app/'.$this->nombre);
        $this->procesado=true;
        $this->save();
        flash($mensaje.$import->getRowCount());
        return true;
    }

    public function procesarPxRad() {
        flash('TODO: Procesar PxRad en archivo')->warning();
        $dbf = $this->procesarDBF();
        try {
            return $procesar_result = MyDB::procesarPxRad(strtolower($this->tabla),'public');
        } catch ( GeoestadisticaException $e ) {
            flash('('.$e->GetCode().') Error. NO SE PUDO PROCESAR PXRAD: '.$e->getMessage())->error()->important();
            $data['file']['pxrad']='none';
            Log::debug('Error cargando data Radio '.$e);
            return view('segmenter/index', ['data' => $data,'epsgs'=> $this->epsgs]);
        }
    }

    public function asociar(Archivo $lab_file_asoc){
        $resulta = MyDB::moverEsquema('e_'.$this->tabla,'e_'.$lab_file_asoc->tabla);
        $ex_tabla = $this->tabla;
        $this->tabla = Str($lab_file_asoc->tabla);
        $this->save();
        MyDB::limpiar_esquema('e_'.$ex_tabla);
        return $resulta;
    }

    // Archivos csv con tabla de viviendas generada en modo manual.
    // x provincia (para PBA) juntando /y corrigiendo)
    public function procesarViviendas() {
        $mensaje = 'Se procesa un csv. Viviendas completos? Resultado: ';
        Excel::import($import, storage_path().'/app/'.$this->nombre);
        $this->procesado=true;
        $this->save();
        flash($mensaje.$import->getRowCount());
        return true;
    }


    // Busca los archivos asociados al .shp repetidos para elimiarlos/reutilizarlos
    public function buscarYBorrarArchivosSHP(Archivo $original){
        Log::info("Es archivo .shp");
        // elimino la extension .shp
        $nombre_original = explode(".",$original->nombre)[0];
        $nombre_copia = explode(".",$this->nombre)[0];

        // busco para cada extension
        $extensiones = [".dbf", ".shx", ".prj"];
        foreach ($extensiones as $extension) {
            Log::info("Verificando archivos con extensión ".$extension." en el storage");
            $o = $nombre_original . $extension;
            $c = $nombre_copia . $extension;
            if(Storage::exists($c)) {
                if(Storage::exists($o)) {
                    # si existe el archivo original entonces elimino la copia
                    Log::info("Existe el archivo ".$extension." original en el storage. Se eliminará la copia");
                    if(Storage::delete($c)){
                        Log::info('Se borró el archivo: '.  $this->nombre_original . " extension " . $extension);
                    }else{
                        Log::error('NO se borró el archivo: '.$this->nombre_original . " extension " . $extension);
                    }
                } else {
                    # si no existe entonces el archivo copia reemplaza al original en el storage (son el mismo por lo que no hay problema)
                    Log::error("No existe el archivo ".$extension." original en el storage. Se reutiliza la copia");
                    # renombro a la copia con el nombre del original inexistente
                    Storage::move($c, $o);
                }
            }
        }
    }

    public function chequearYBorrarStorage(Archivo $original){
        Log::info("Verificando storage");
        $copia = $this;
        if(Storage::exists($original->nombre)) {
            # si existe el archivo original entonces elimino la copia
            Log::info("Existe el archivo original en el storage. Se eliminará la copia");
            if(Storage::delete($copia->nombre)){
                Log::info('Se borró el archivo: '.$copia->nombre. ' nombre original:'. $copia->nombre_original);
            }else{
                Log::error('NO se borró el archivo: '.$copia->nombre. ' nombre original:'.$copia->nombre_original);
            }
        } else {
            # si no existe entonces el archivo copia reemplaza al original en el storage (son el mismo por lo que no hay problema)
            Log::error("No existe el archivo original en el storage. Se reutiliza la copia");
            $original->update(['nombre' => $copia->nombre]);
        }
    }

    public function limpiar_copia(Archivo $original){
        $owner = $this->user;
        # En caso de ser necesario le permito al usuario ver el archivo original
        if (($original->user_id != $owner->id) and (!$owner->visible_files()->get()->contains($original))){
            $owner->visible_files()->attach($original->id);
            Log::info("Archivo original (".$original->id.") agregado a file_viewer del owner de la copia (".$this->id.")");
        }

        # Verifico que existan los archivos originales en el storage
        $this->chequearYBorrarStorage($original);
        # si es multiarchivo elimino tambien las copias de los demas archivos
        if ($this->ismultiArchivo()){
            $this->buscarYBorrarArchivosSHP($original);
        }

        # Si la copia es vista por otros usuarios
        $viewers = $this->viewers()->get();
        if ($viewers != null){
            foreach ($viewers as $viewer){
                if (!$viewer->visible_files()->get()->contains($original)){
                    # Si el viewer no es viewer del archivo original creo la relación
                    $viewer->visible_files()->attach($original->id);

                }
            }
            Log::info("Los viewers de la copia ". $this->id ." ahora son viewers del archivo original ".$original->id);
            # Elimino la relación con todos los viewers
            $this->viewers()->detach();
            Log::info("Se eliminaron los viewers de la copia.");
        }
        $this->delete();
        Log::info("Se eliminó el registro perteneciente a la copia");
    }

    public function getesCopiaAttribute(){
        $original = Archivo::where('checksum',$this->checksum)->orderby('id','asc')->first();
        $es_copia = $original->id != $this->id;
        if ($es_copia) {
            Log::warning($this->nombre_original." es copia!");
        } else {
            Log::info($this->nombre_original." es el archivo original!");
        }
        return $es_copia;
    }

}
