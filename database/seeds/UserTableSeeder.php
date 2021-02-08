<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          DB::table('users')->insert([
              [
                'name' => 'Usuaio',
                'lastname' => 'de',
                'o_lastname' => 'Prueba',
                'email' => 'soporte@deinsi.com',
                'password' => bcrypt('test'),
                'role' => '1',
                'status' => 'ACTIVO',
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now(),
              ]
          ]);
    }
}
