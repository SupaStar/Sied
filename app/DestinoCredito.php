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

  public function destino()
  {
    return $this->hasOne("App\DestinoRecursos", "id", "id_destino_recursos");
  }
}
