<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlSubtipoViviendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Sembrando subtipo_viviendas...');
        $path = 'app/developer_docs/subtipo_vivienda.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(QueryException $e){
                if ($e->getCode()==23505){
                    $this->command->error('SubtipoViviendas NO fueron plantados!');
                    return 0;
                }
            }
        $this->command->info('SubtipoViviendas plantados!');
    }
}
