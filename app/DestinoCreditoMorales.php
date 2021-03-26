<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinoCreditoMorales extends Model
{
  protected $table = "destino_credito_morales";

  public function credito_moral()
  {
    return $this->hasOne('App\Credito_Moral', 'id', 'id_credito_moral');
  }

  public function destino()
  {
    return $this->hasOne('App\DestinoRecursos', 'id', 'id_destino_recursos');
  }
}
