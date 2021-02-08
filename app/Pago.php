<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
      'client_id',
      'credito_id',
      'pago',
      'fpago',
      'periodo',
      'moneda',
      'origen',
      'forma'
    ];
}
