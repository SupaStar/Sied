<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinoCredito extends Model
{
  protected $table = "destino_credito";

  public function credito()
  {
    return $this->hasOne("App\Creditos", "id", "id_credito");
  }

  public function origen()
  {
    return $this->hasOne("App\OrigenRecursos", "id", "id_origen_recursos");
  }
}
