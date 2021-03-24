<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialFlujos_Morales extends Model
{
  protected $table = 'historial_flujos_morales';

  public function amortizacion()
  {
    return $this->hasOne('App\Amortizacion_Morales', 'id', 'amortizacion_id');
  }
}
