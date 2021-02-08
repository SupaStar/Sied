<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelacionPagos extends Model
{
    protected $table = 'relacion_pagos';

    protected $fillable = [
      'periodo_id',
      'pago_id',
      'fecha_pago',
      'monto',
      'monto_total',
      'restante',
      'pago_restante',
      'descripcion'
    ];
}
