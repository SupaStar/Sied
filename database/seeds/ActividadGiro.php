<?php

use Illuminate\Database\Seeder;

class ActividadGiro extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actividad_giro')->insert([
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
