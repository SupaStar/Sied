<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprobantePagosMorales_Morales extends Model
{
  protected $table = 'comprobante_pagos_morales';

  public function pago()
  {
    return $this->hasOne('App\Pago_Moral', 'id', 'pago_moral_id');
  }
}
