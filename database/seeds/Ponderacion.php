<?php

use Illuminate\Database\Seeder;

class Ponderacion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('ponderacion')->insert([
        [
          'factor' => 'Destino Recursos',
          'ponderacion' => 100,
          'tipo' => 'destino'
        ],
        [
          'factor' => 'Origen de Recursos',
          'ponderacion' => 40,
          'tipo' => 'origen'
        ],
        [
          'factor' => 'Divisa',
          'ponderacion' => 20,
          'tipo' => 'origen'
        ],
        [
          'factor' => 'Instrumento Monetario',
          'ponderacion' => 40,
          'tipo' => 'origen'
        ],
        [
          'factor' => 'Actividad o Giro',
          'ponderacion' => 40,
          'tipo' => 'actividad'
        ],
        [
          'factor' => 'Profesion',
          'ponderacion' => 60,
          'tipo' => 'actividad'
        ],
        [
          'factor' => 'Alertas PLD/FT',
          'ponderacion' => 20,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Edad',
          'ponderacion' => 5,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Antiguedad',
          'ponderacion' => 5,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Personalidad Juridica',
          'ponderacion' => 15,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Confirmado Listas PEP Mexicano',
          'ponderacion' => 30,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Confirmado Listas PEP Extranjero',
          'ponderacion' => 0,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Nacionalidad',
          'ponderacion' => 10,
          'tipo' => 'antecedentes'
        ],
        [
          'factor' => 'Entidad Federativa Residencia',
          'ponderacion' => 15,
          'tipo' => 'antecedentes'
        ]

      ]);
    }
}
