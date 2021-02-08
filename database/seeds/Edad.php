<?php

use Illuminate\Database\Seeder;

class Edad extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('edad')->insert([
            [
               'descripcion' => 'MENORES 22 AÑOS',
               'puntaje' => 30
             ],
             [
               'descripcion' => 'DE 23 A 30 AÑOS',
               'puntaje' => 20
             ],
             [
               'descripcion' => 'DE 31 A 50 AÑOS',
               'puntaje' => 20
             ],
             [
               'descripcion' => 'DE 51 A 99 AÑOS',
               'puntaje' => 30
             ],
          ]);
    }
}
