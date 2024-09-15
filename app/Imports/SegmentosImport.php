<?php

namespace App\Imports;

use App\Model\Segmento;
use App\User;
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
//use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::extend(
    'custom', function ($value, $key) {
        return trim(strtolower(explode(',', $value)[0])); 
    }
);

class SegmentosImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueue, WithCustomCsvSettings, WithEvents //, WithProgressBar
{
    use Importable,RegistersEventListeners;
    private $rows = 0;
    private $importedBy;

    public function __construct(User $importedBy = null)
    {
        $this->importedBy = $importedBy;
    }

    /**
     * @return string|array
     */
    /*
    Laravel 8 UPSERTS Eloquent
    public function uniqueBy()
    {
        return 'prov, nom_prov, dpto, nom_dpto, codloc, nom_loc, codent, nom_ent, frac, radio, tipo, seg, vivs';
    }
    */

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->rows;
    //    return Segmento::firstOrCreate(
        return new Segmento(
            [
            'prov' => $row['prov'],
            'nom_prov' => $row['nom_prov'] ?? $row['nomprov'],
            'dpto' => $row['dpto'] ?? $row['depto'],
            'nom_dpto' => $row['nom_dpto'] ?? $row['nomdepto'] ,
            'codloc' => $row['codloc'],
            'nom_loc' => $row['nom_loc'] ?? $row['nomloc'] ,
            'codent' => $row['codent'] ?? 0,
            'nom_ent' => $row['nom_ent'] ?? '',
            'frac' => $row['frac'],
            'radio' => $row['radio'],
            'tipo' => $row['tipo'] ?? 'I',
            'seg' => $row['segmento'] ?? $row['seg'],
            'vivs' => $row['cantviv'] ?? 0,
            ]
        );
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
            'delimiter' => '|'
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
        if ($this->importedBy) {
            return ['csv', 'user:'.$this->importedBy];
        } else {
            return ['csv', 'sin user'];
        }
    }

}


