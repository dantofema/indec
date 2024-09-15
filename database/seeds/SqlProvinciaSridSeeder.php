<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlProvinciaSridSeeder extends Seeder
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
        $this->command->info('Sembrando srid de provincias...');
        $path = 'app/developer_docs/provincias_srid.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(QueryException $e){
                if ($e->getCode()==23505){
                    $this->command->error('srid de provincias NO fueron plantadas!');
                    return 0;
                }
            }
        $this->command->info('srid de provincias plantadas!');
    }
}
