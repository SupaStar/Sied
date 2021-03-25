<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amortizacion_Morales extends Model
{
  protected $table = 'amortizaciones_morales';
  protected $fillable = [
    'moral_id',
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

  public function moral()
  {
    return $this->hasOne('App\Moral', 'id', 'moral_id');
  }

  public function credito()
  {
    return $this->hasOne('App\Credito_Moral', 'id', 'credito_id');
  }
}
