<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlDeptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Sembrando departamentos...');
        $path = 'app/developer_docs/departamentos.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Departamentos plantados!');
    }
}
