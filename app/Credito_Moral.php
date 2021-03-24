<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito_Moral extends Model
{
  protected $table = 'credito_morales';

  public function moral()
  {
    $this->hasOne('App\Moral', 'id', 'moral_id');
  }

  public function amortizaciones()
  {
    $this->hasMany('App\Amortizacion_Morales', 'credito_id', 'id');
  }
}
