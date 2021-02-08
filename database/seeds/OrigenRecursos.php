<?php

use Illuminate\Database\Seeder;

class OrigenRecursos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('origen_recursos')->insert([
            [
               'descripcion' => 'Sueldos',
               'puntaje' => 10
             ],
             [
               'descripcion' => 'Servicios Profesionales',
               'puntaje' => 15
             ],
             [
               'descripcion' => 'Actividad',
               'puntaje' => 15
             ],
             [
                'descripcion' => 'Actividades Financieras',
                'puntaje' => 20
             ],
             [
               'descripcion' => 'Herencias',
               'puntaje' => 40
             ]
          ]);
 }
}
