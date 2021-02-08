<?php

use Illuminate\Database\Seeder;

class Profesion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profesion')->insert([
            [
               'descripcion' => 'Informalidad',
               'puntaje' => 30
             ],
             [
               'descripcion' => 'Alto Flujo de Efectivo',
               'puntaje' => 30
             ],
             [
               'descripcion' => 'Operaciones con otras divisas',
               'puntaje' => 30
             ],
             [
                'descripcion' => 'Otro',
                'puntaje' => 10
             ]
          ]);
    }
}
