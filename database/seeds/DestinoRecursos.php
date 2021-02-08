<?php

use Illuminate\Database\Seeder;

class DestinoRecursos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('destino_recursos')->insert([
            [
               'descripcion' => 'Capital de Trabajo',
               'puntaje' => 20
             ],
             [
               'descripcion' => 'Compra Materia Prima/Activos',
               'puntaje' => 20
             ],
             [
               'descripcion' => 'Liquidacion de Pasivos',
               'puntaje' => 30
             ],
             [
               'descripcion' => 'Otros',
               'puntaje' => 30
             ]
        ]);
  }
}
