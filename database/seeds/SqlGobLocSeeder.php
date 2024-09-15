<?php

use Illuminate\Database\Seeder;

class SqlGobLocSeeder extends Seeder
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
        $this->command->info('Sembrando gobiernos locales...');
        $path = 'app/developer_docs/gobierno_local.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Gobiernos Locales plantados!');
    }
}
