<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Validator;
use Config;
use Maatwebsite\Excel\Facades\Excel;
use App\Flag;

class ImportManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excelfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar archivo excel (csv)';

    /**
     *
     * Cantidad de registros procesados por lote
     * 
    **/
    protected $chunkSize = 500;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
       $file = Flag::where('imported','=','0')
                   ->orderBy('created_at', 'DESC')
                   ->first();

       $file_path = Config::get('filesystems.disks.local.root') . '/' .$file->file_name;

      // let's first count the total number of rows
       Excel::load($file_path, function($reader) use($file) {
           $objWorksheet = $reader->getActiveSheet();
           $file->total_rows = $objWorksheet->getHighestRow() - 1; //exclude the heading
           $file->save();
       });

      //now let's import the rows, one by one while keeping track of the progress
       Excel::filter('chunk')
           ->selectSheetsByIndex(0)
           ->load($file_path)
           ->chunk($this->chunkSize, function($result) use ($file) {
               $rows = $result->toArray();
              //let's do more processing (change values in cells) here as needed
               $counter = 0;
               foreach ($rows as $k => $row) {
                   foreach ($row as $c => $cell) {
                       $rows[$k][$c] = $cell . ':)'; //altered value :)
                   }
                   DB::table('data')->insert( $rows[$k] );
                   $counter++;
               }
               $file = $file->fresh(); //reload from the database
               $file->rows_imported = $file->rows_imported + $counter;
               $file->save();
           }
       );

       $file->imported =1;
       $file->save();
    }
}
