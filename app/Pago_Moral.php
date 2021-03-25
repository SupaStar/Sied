<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Moral extends Model
{
  protected $table = 'pago_morales';
  protected $fillable = [
    'moral_id',
    'credito_id',
    'pago',
    'fpago',
    'periodo',
    'moneda',
    'origen',
    'forma'
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
