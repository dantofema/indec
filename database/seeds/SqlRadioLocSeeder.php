<?php

use Illuminate\Database\Seeder;

class SqlRadioLocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->command->info('Relacionando Radios con Localidades ...');
        $path = 'app/developer_docs/radio_localidad.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Radios relacionados a Localidades!');
    }
}
