<?php

use Illuminate\Database\Seeder;

class Antiguedad extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('antiguedad')->insert([
            [
               'descripcion' => 'MENOR A 2 AÑOS',
               'puntaje' => 50
             ],
             [
               'descripcion' => 'DE 2 A 4 AÑOS',
               'puntaje' => 16
             ],
             [
               'descripcion' => 'DE 4 A 9 AÑOS',
               'puntaje' => 16
             ],
             [
               'descripcion' => 'MAS DE 10 AÑOS',
               'puntaje' => 4
             ],
          ]);
    }
}
