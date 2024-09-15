<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlFuenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        $this->command->info('Sembrando fuentes...');
        $path = 'app/developer_docs/fuente.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(QueryException $e){
                if ($e->getCode()==23505){
                    $this->command->error('Fuentes NO fueron plantadas!');
                    return 0;
                }
            }
        $this->command->info('Fuentes plantadas!');
    }
}
