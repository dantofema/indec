<?php

use Illuminate\Database\Seeder;

class SqlRadioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->command->info('Sembrando radios...');
        $path = 'app/developer_docs/radio.sql';
        try{
            DB::unprepared(file_get_contents($path));
            }catch(Exception $e){
                if ($e->getCode()==23505){
                    $this->command->error('Radios NO fueron plantados!');
                    return 0;
                }
            }
        $this->command->info('Radios plantados!');
    }
}
