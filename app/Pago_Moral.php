<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_Moral extends Model
{
  protected $table = 'pago_morales';

  public function moral()
  {
    return $this->hasOne('App\Moral', 'id', 'moral_id');
  }

  public function credito()
  {
    return $this->hasOne('App\Credito_Moral', 'id', 'credito_id');
  }
}
