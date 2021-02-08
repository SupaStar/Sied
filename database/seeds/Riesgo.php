<?php

use Illuminate\Database\Seeder;

class Riesgo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('riesgo')->insert([
           [
             'riesgo' => 'BAJO',
             'minimo' => 17.46,
             'maximo' => 24.5,
           ],
           [
             'riesgo' => 'MEDIO',
             'minimo' => 24.5,
             'maximo' => 31.5,
           ],
           [
             'riesgo' => 'ALTO',
             'minimo' => 31.5,
             'maximo' => 37.54,
           ]
        ]);
    }
}
