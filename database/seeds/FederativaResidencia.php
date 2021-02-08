<?php

use Illuminate\Database\Seeder;

class FederativaResidencia extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entidad_federativa_residencia')->insert([
            [
               'descripcion' => 'Alto',
               'puntaje' => 50
            ],
            [
               'descripcion' => 'Medio',
               'puntaje' => 30
            ],
            [
              'descripcion' => 'Bajo',
              'puntaje' => 20
            ]
          ]);
    }
}
