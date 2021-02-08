<?php

use Illuminate\Database\Seeder;

class Factores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('factores')->insert([
            [
               'riesgo' => 'Bajo',
               'minimo' => 17.46,
               'maximo' => 24.5
            ],
            [
                'riesgo' => 'Mediano',
                'minimo' => 24.5,
                'maximo' => 31.5
             ],
            [
                'riesgo' => 'Alto',
                'minimo' => 31.5,
                'maximo' => 37.54
             ]
          ]);
    }
}
