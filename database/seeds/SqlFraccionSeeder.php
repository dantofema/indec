<?php

use Illuminate\Database\Seeder;

class SqlFraccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->command->info('Sembrando Fracciones...');
        $path = 'app/developer_docs/fraccion.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Fraccion plantados!');
    }
}
