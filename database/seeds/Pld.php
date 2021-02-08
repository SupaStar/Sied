<?php

use Illuminate\Database\Seeder;

class Pld extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pld')->insert([
            [
               'descripcion' => 'Si',
               'puntaje' => 0
             ],
             [
               'descripcion' => 'No',
               'puntaje' => 0
             ]
          ]);
    }
}
