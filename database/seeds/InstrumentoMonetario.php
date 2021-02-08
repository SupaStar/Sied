<?php

use Illuminate\Database\Seeder;

class InstrumentoMonetario extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instrumento_monetario')->insert([
            [
               'descripcion' => 'Efectivo',
               'puntaje' => 70
            ],
            [
               'descripcion' => 'Transferencia',
               'puntaje' => 10
            ],
            [
              'descripcion' => 'Cheques',
              'puntaje' => 20
            ]
          ]);
    }
}
