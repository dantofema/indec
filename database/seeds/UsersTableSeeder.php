<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     $user = DB::table('users')->where('email', 'indec@indec.com')->first();
     if ($user === null) {
      // user doesn't exist
        DB::table('users')->insert([
            'name' => 'Ale',
            'email' => 'indec@indec.com',
            'password' => bcrypt('password'),
        ]);
      }

     $user = DB::table('users')->where('email', 'manuel@retamozo.com.ar')->first();
     if ($user === null) {
      // user doesn't exist
        DB::table('users')->insert([
            'name' => 'Manu',
            'email' => 'manuel@retamozo.com.ar',
            'password' => bcrypt('adminadmin'),
        ]);
      }

     $user = DB::table('users')->where('email', 'admin@geoinquietos')->first();
     if ($user === null) {
      // user doesn't exist
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@geoinquietos',
            'password' => bcrypt('adminadmin'),
        ]);
     };
    }
}
