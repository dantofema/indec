<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlOperativosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->command->info('Sembrando operativos...');
        $path = 'app/developer_docs/operativos.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(QueryException $e){
                if ($e->getCode()==23505){
                    $this->command->error('Operativos NO fueron plantados!');
                    return 0;
                }
            }
        $this->command->info('Operativos plantados!');
    }
}
