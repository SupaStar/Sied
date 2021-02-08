<?php

use Illuminate\Database\Seeder;

class NacionalidadAntecedentes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nacionalidad_antecedentes')->insert([
            [
               'descripcion' => 'Mexicana',
               'puntaje' => 30
            ],
            [
               'descripcion' => 'Extranjera',
               'puntaje' => 70
            ]
          ]);
    }
}
