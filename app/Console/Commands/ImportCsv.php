<?php

namespace App\Console\Commands;

use App\Imports\CsvImport;
use Illuminate\Console\Command;

class ImportCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excel CSV Domiciios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravel Excel importer';

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
        $this->output->title('Starting import');
        (new CsvImport)->withOutput($this->output)->import('domicilios.csv');
        $this->output->success('Import successful');

    }
}
