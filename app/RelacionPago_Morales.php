<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelacionPago_Morales extends Model
{
  protected $table = 'relacion_pagos_morales';

  public function pago()
  {
    return $this->hasOne('App\Pago_Moral', 'id', 'pago_moral_id');
  }

  public function amortizacion()
  {
    return $this->hasOne('App\Amortizacion_Morales', 'id', 'amortizacion_moral_id');
  }
}
