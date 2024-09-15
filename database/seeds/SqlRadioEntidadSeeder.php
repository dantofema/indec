<?php

use Illuminate\Database\Seeder;

class SqlRadioEntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->command->info('Sembrando relacion radios entidades...');
        $path = 'app/developer_docs/radio_entidad.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(Exception $e){
                if ($e->getCode()==23505){
                    $this->command->error('Relación Radios - Entidades NO fue plantada!');
                    return 0;
                }
            }
        $this->command->info('Relación Radios - Entidades plantada!');
        //
    }
}
