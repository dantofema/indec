<?php

use Illuminate\Database\Seeder;

class SqlLocalidadGobLocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Eloquent::unguard();
        $this->command->info('Relacionando Localidades con Gobiernos Locales...');
        $path = 'app/developer_docs/localidad_gobierno_local.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Localidades relacionadas a Gobiernos Locales!');
    }
}
