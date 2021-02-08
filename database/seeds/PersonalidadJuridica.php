<?php

use Illuminate\Database\Seeder;

class PersonalidadJuridica extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personalidad_juridica')->insert([
            [
               'descripcion' => 'Persona Fisica',
               'puntaje' => 25
            ],
            [
               'descripcion' => 'Persona Moral',
               'puntaje' => 20
            ],
            [
               'descripcion' => 'PFAE',
               'puntaje' => 20
            ],
            [
               'descripcion' => 'Fideicomiso',
               'puntaje' => 35
            ],
        ]);
    }
}
