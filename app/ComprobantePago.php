<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprobantePago extends Model
{
  protected $table = 'comprobantes_pago';

  protected $fillable = [
    'pago_id',
    'path',
    'extension',
    'name',
    'full',
    'user_id'
  ];
}
