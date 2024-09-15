<?php

namespace App\Imports;

use App\Domicilio;
use App\Notifications\ImportHasFailedNotification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Support\Facades\Log;

class CsvImport implements ToModel,WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueue, WithCustomCsvSettings, WithEvents //, WithProgressBar
{
    use Importable,RegistersEventListeners;
    private $rows = 0;
    private $importedBy;


    public function __construct(User $importedBy = null)
    {
        $this->importedBy = $importedBy;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
	 ++$this->rows;
//	die(var_dump($row));
/*
/* Cabecera de csv de PBA con problemas, vino latin1 y lo pase a utf8 con iconv.
/* PROV, NOM_PROVIN, UPS, NRO_AREA, DPTO, NOM_DPTO, CODAGLO, CODLOC, NOM_LOC, CODENT, NOM_ENT, FRAC, RADIO, MZA, LADO, NRO_INICIA, NRO_FINAL, ORDEN_RECO, NRO_LISTAD, CCALLE, NCALLE, NROCATASTR, PISOREDEF, CASA, DPTO_HABIT, SECTOR, EDIFICIO, ENTRADA, COD_TIPO_V, DESCRIPCIO, DESCRIPCI2, COD_POSTAL, ORDEN_REC2, FECHA_RELE, SEGMENTO
/*
 */
        // toLowerCase 
        $row = array_change_key_case($row, CASE_LOWER);
        return new Domicilio([
'prov' => $row['prov'] ?? '99',
'listado_id' => '0' ?? $row['codaglo'] ?? '1',
'nom_provin' => $row['nom_provin'] ?? $row['nom_provincia'],
'dpto' => $row['dpto'],
'nom_dpto' => $row['nom_dpto'],
'codaglo' => $row['codaglo'],
'codloc' => $row['codloc'],
'nom_loc' => $row['nom_loc'],
'codent' => $row['codent'],
'nom_ent' => $row['nom_ent'],
'frac' => $row['frac'],
'radio' => $row['radio'],
'mza' => $row['mza'],
'lado' => $row['lado'],
'nro_inicia' => $row['nro_inicia'],
'nro_final' => $row['nro_final'],
'orden_reco' => $row['orden_reco'] ?? $row['orden_recorrido_viv'] ?? $row['orden_rec2'],
'nrolist' => $row['nrolist'] ?? $row['nro_listad'] ?? $row['nro_listado'] ?? 0,
'ccalle' => $row['ccalle'],
'ncalle' => $row['ncalle'],
'nrocatastr' => $row['nrocatastr'] ?? $row['nrocatastralredef'] ?? $row['nro_catastral'],
'piso' => $row['pisoredef'] ?? $row['piso'],
'casa' => $row['casa'],
'dptohab' => $row['dptohab'] ?? $row['dpto_habitacion'] ?? $row['dpto_habit'],
'sector' => $row['sector'],
'edificio' => $row['edificio'],
'entrada' => $row['entrada'],
'tipoviv' => $row['tipovivredef'] ?? $row['tipoviv'] ?? $row['cod_tipo_v'] ?? null,
'descrip' => $row['descrip'] ?? $row['descripcio'] ?? null,
'descripl' => $row['descripl'] ?? $row['descripci2'] ?? null,
'cpostal' => $row['cpostal'] ?? $row['cod_postal'] ?? null,
'ordrecmza' => $row['ordrecmza'] ?? $row['orden_rec2'] ?? null,
'fechrele' => $row['fechrele'] ?? $row['fecha_rele'] ?? null,
'tiptarea' => $row['tiptarea'] ?? null,
'segmento' => $row['segmento'] ?? null
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => '|',
            'enclosure' => ''
        ];
    }

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->importedBy->notify(new ImportHasFailedNotification);
            },
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public static function afterImport(AfterImport $event)
    {
        //
	      echo 'Rows: '.$this->rows;
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {	
	if ($this->importedBy){
	        return ['csv', 'user:'.$this->importedBy];
	}
	else{
	        return ['csv', 'sin user'];
	}
    }

}


