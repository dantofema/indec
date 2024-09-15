<?php

use Illuminate\Database\Seeder;

class SqlLocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->command->info('Sembrando localidades...');
        $path = 'app/developer_docs/localidad.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Localidades plantados!');
    }
}
