<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table = 'credito_morales';

    protected $fillable = [
      'client_id',
      'tcredito',
      'contrato',
      'monto',
      'fpago',
      'frecuencia',
      'plazo',
      'amortizacion',
      'iva',
      'tasa',
      'disposicion',
      'status'
    ];

    //
}
