<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SqlProvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
          $this->command->info('Provincias table seed!');
          $path = 'app/developer_docs/provincias.sql';
          DB::unprepared(file_get_contents($path));
        }catch(QueryException $e){
             if ($e->getCode()==23505){
                 $this->command->error('Provincias NO fueron plantados (ya existían)!');
                 return 0;
            }
        }
        $this->command->info('Provincias table seeded!');
    }
}
