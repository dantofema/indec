<?php

use Illuminate\Database\Seeder;

class SqlGobLocEntidadSeeder extends Seeder
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
        $this->command->info('Relacionando Gobiernos Locales con Entidades ...');
        $path = 'app/developer_docs/gobierno_local_entidad.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Gobiernos Locales relacionados a Entidades!');
    }
}
