<?php

use Illuminate\Database\Seeder;

class PepMexicanas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pep_mexicanas')->insert([
          [
             'descripcion' => 'Si',
             'puntaje' => 80
           ],
           [
             'descripcion' => 'No',
             'puntaje' => 20
           ]
          ]);
    }
}
