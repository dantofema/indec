<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // RoleSeeder::class,
    		UsersTableSeeder::class,
	        // Bunch of seeders using Eloquent
            // Provincias
	         SqlProvSeeder::class,
            SqlProvinciaSridSeeder::class,
    //       SqlDeptoSeeder::class,
    //       SqlLocSeeder::class,
    //       SqlLocDeptoSeeder::class,
    //       SqlAgloSeeder::class,
    //       SqlGobLocSeeder::class,
    //       SqlFraccionSeeder::class,
    //       SqlRadioSeeder::class,
    //       SqlRadioLocSeeder::class,
    //       SqlEntidadSeeder::class,
    //       SqlGobLocEntidadSeeder::class,
    //       SqlGobLocDeptoSeeder::class,
    //       SqlLocalidadGobLocSeeder::class,
            SqlParajeSeeder::class,
            SqlOperativosSeeder::class,
            SqlFuenteSeeder::class,
            SqlSubtipoViviendaSeeder::class,
       //     SqlRadioEntidadSeeder::class
            SqlProvTestSeeder::class
        ]);
    }
}
