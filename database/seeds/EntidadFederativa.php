<?php

use Illuminate\Database\Seeder;

class EntidadFederativa extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entidad_federativa')->insert([
                [
                    'entity' => 'Aguascalientes',
                    'code' => 'AS'
                ],
                [
                    'entity' => 'Baja California',
                    'code' => 'BC'
                ],
                [
                    'entity' => 'Baja California Sur',
                    'code' => 'BS'
                ],
                [
                    'entity' => 'Campeche',
                    'code' => 'CC'
                ],
                [
                    'entity' => 'Chiapas',
                    'code' => 'CS'
                ],
                [
                    'entity' => 'Chihuahua',
                    'code' => 'CH'
                ],
                [
                    'entity' => 'Coahuila',
                    'code' => 'CL'
                ],
                [
                    'entity' => 'Colima',
                    'code' => 'CM'
                ],
                [
                    'entity' => 'Distrito Federal',
                    'code' => 'DF'
                ],
                [
                    'entity' => 'Durango',
                    'code' => 'DG'
                ],
                [
                    'entity' => 'Guanajuato',
                    'code' => 'GT'
                ],
                [
                    'entity' => 'Guerrero',
                    'code' => 'GR'
                ],
                [
                    'entity' => 'Hidalgo',
                    'code' => 'HG'
                ],
                [
                    'entity' => 'Jalisco',
                    'code' => 'JC'
                ],
                [
                    'entity' => 'México',
                    'code' => 'MC'
                ],
                [
                    'entity' => 'Michoacan',
                    'code' => 'MN'
                ],
                [
                    'entity' => 'Morelos',
                    'code' => 'MS'
                ],
                [
                    'entity' => 'Nayarit',
                    'code' => 'NT'
                ],
                [
                    'entity' => 'Nuevo León',
                    'code' => 'NL'
                ],
                [
                    'entity' => 'Oaxaca',
                    'code' => 'OC'
                ],
                [
                    'entity' => 'Puebla',
                    'code' => 'PL'
                ],
                [
                    'entity' => 'Querétaro',
                    'code' => 'QT'
                ],
                [
                    'entity' => 'Quintana Roo',
                    'code' => 'QR'
                ],
                [
                    'entity' => 'San Luis Potosí',
                    'code' => 'SP'
                ],
                [
                    'entity' => 'Sinaloa',
                    'code' => 'SL'
                ],
                [
                    'entity' => 'Sonora',
                    'code' => 'QR'
                ],
                [
                    'entity' => 'Quintana Roo',
                    'code' => 'SR'
                ],
                [
                    'entity' => 'Tabasco',
                    'code' => 'TC'
                ],
                [
                    'entity' => 'Tlaxcala',
                    'code' => 'TL'
                ],
                [
                    'entity' => 'Tamaulipas',
                    'code' => 'TS'
                ],
                [
                    'entity' => 'Veracruz',
                    'code' => 'VZ'
                ],
                [
                    'entity' => 'Yucatán',
                    'code' => 'YN'
                ],
                [
                    'entity' => 'Zacatecas',
                    'code' => 'ZS'
                ]
            ]);        	
    }
}
