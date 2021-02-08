<?php

use Illuminate\Database\Seeder;

class PepExtranjeras extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pep_extranjeras')->insert([
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
