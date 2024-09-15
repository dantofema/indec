<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlParajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->command->info('Sembrando parajes...');
        $path = 'app/developer_docs/paraje.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(QueryException $e){
                if ($e->getCode()==23505){
                    $this->command->error('Parajes NO fueron plantados!');
                    return 0;
                }
            }
        $this->command->info('Parajes plantados!');
    }
}
