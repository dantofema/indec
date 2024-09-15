<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Config;
use Auth;
use App\MyDB;

class Segmentador extends Model
{
    private $resultado=null;
    
    public function segmentar_a_lado_completo($aglo,$prov,$dpto,$frac,$radio,$vivs_deseada,$vivs_max,$vivs_min,$mza_indivisible)
	{
	if (Auth::check()) {
	$AppUser= Auth::user();
        // Set the limit to 500 MB.
        // Varios intentos para aumentar la memoria.
        /*
        $fiveMBs = 500 * 1024 * 1024;
        $fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');
        fwrite($fp, 'test');
        rewind($fp);
        ini_set('memory_limit','512M');
        */
        $processLog = Process::fromShellCommandline('echo "$tiempo: $usuario_name ($usuario_id) -> va a segmentar: $info_segmenta"  >> segmentaciones.log');
        $processLog->run(null, ['info_segmenta' => " Aglomerado: ".$aglo ." Frac ".$frac." Radio ".$radio,
                                'usuario_id' => $AppUser->id,
                                'usuario_name' => $AppUser->name,
                                'tiempo' => date('Y-m-d H:i:s')]);

        $esquema = 'e'.$aglo;

        // Ejemplo: python3 app/developer_docs/segmentacion-core/lados_completos/lados_completos.py e0777.arc 50 084 1 4 20 30 10 1 
	$process = Process::fromShellCommandline('/usr/bin/python3 ../app/developer_docs/segmentacion-core/lados_completos/lados_completos.py $tabla $prov $dpto $frac $rad $min $max $deseada $indivisible usar_todos_juntos costo_cuadratico_mzas pondera_viviendas',null,['PYTHONIOENCODING' => 'utf8',
		'MANDARINA_DATABASE' => Config::get('database.connections.pgsql.database'),
		'MANDARINA_USER' => Config::get('database.connections.pgsql.username'),
		'MANDARINA_PASS' => Config::get('database.connections.pgsql.password'),
		'MANDARINA_HOST' => Config::get('database.connections.pgsql.host'),
		'MANDARINA_PORT' => Config::get('database.connections.pgsql.port')
	]);
        $process->setTimeout(5 * 60 * 60);
       
        $process->run(null, ['tabla' => $esquema.".arc",'prov'=>$prov,'dpto'=>$dpto,'frac'=>$frac,'rad'=>$radio,
                             'deseada'=>$vivs_deseada,'max'=>$vivs_max,'min'=>$vivs_min,'indivisible'=>$mza_indivisible]);
                        // executes after the command finishes
                        if (!$process->isSuccessful()) {
                                Log::error($process->getErrorOutput());
                                flash('No se pudo correr la segmentación! ')->error()->important();
                                return $this->resultado='No se pudo correr segmentación.';
                        }else{  
                            MyDB::lados_completos_a_tabla_segmentacion_ffrr($aglo,$frac,$radio);
                            return $this->resultado=$process->getOutput();
                        }
	// e0777.arc 50 084 1 4 20 30 10 1');
	}else{
	   flash('No tiene permisos para segmentar o no está logueado. Maybe be reported!')->warning()->important();
	   return 'No tiene permisos para segmentar o no está logueado';
	}
     }

    public function ver_segmentacion()
    {
       if (isset($this->resultado)){
            return $this->resultado;
        }else{ return "No hay segmentacion realizada.";}
    }

    public function vista_segmentos_lados_completos($esquema)
    {
        MyDB::recrea_vista_segmentos_lados_completos($esquema);
    }

    public function
    lados_completos_a_tabla_segmentacion_ffrr($esquema,$frac,$radio)
    {
       MyDB::lados_completos_a_tabla_segmentacion_ffrr($esquema,$frac,$radio);
       MyDB::grabarSegmentacion($esquema,$frac,$radio);
        
    }

    public function
    segmentar_excedidos_ffrr($esquema,$frac,$radio,$umbral,$desado)
    {
       MyDB::segmentar_excedidos_ffrr($esquema,$frac,$radio,$umbral,$desado);
       MyDB::juntar_segmentos('e'.$esquema,$frac,$radio);
	     MyDB::grabarSegmentacion($esquema,$frac,$radio);
    }

    public function segmentar_equilibrado_ffrr($esquema,$radio){
      MyDB::segmentar_equilibrado($esquema,$radio);
    }
}
