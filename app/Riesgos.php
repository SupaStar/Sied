<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Riesgos extends Model
{
  protected $table = "riesgos";

  public function cliente()
  {
    return $this->hasOne("App\Client", 'id', 'id_cliente');
  }
}
