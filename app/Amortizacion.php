<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amortizacion extends Model
{
    protected $table = 'amortizaciones_morales';

    protected $fillable = [
      'cliente_id',
      'credito_id',
      'periodo',
      'fechas',
      'inicio',
      'fin',
      'dias',
      'disposicion',
      'saldo_insoluto',
      'comision',
      'amortizacion',
      'intereses',
      'moratorios',
      'iva',
      'flujo',
      'dias_mora',
      'int_mora',
      'iva_mora',
      'pagos',
      'gcobranza',
      'liquidado',
      'dia_mora'
    ];
}
