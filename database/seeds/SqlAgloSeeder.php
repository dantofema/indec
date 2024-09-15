<?php

use Illuminate\Database\Seeder;

class SqlAgloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

//        $this->call('SqlSeeder');
        $this->command->info('Sembrando aglomerados...');
        $path = 'app/developer_docs/aglomerados.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Aglomerados plantados!');
    }
}
