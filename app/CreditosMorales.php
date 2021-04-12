<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditosMorales extends Model
{
    protected $table = 'credito_morales';

    protected $fillable = [
      'morales_id',
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
