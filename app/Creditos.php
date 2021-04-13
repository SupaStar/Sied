<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creditos extends Model
{
    protected $table = 'credito';

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
}
