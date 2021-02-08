<?php

use Illuminate\Database\Seeder;

class Divisas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisa')->insert([
            [
               'descripcion' => 'Moneda Nacional',
               'puntaje' => 30
             ],
             [
               'descripcion' => 'Otra',
               'puntaje' => 70
             ]
        ]);
    }
}
