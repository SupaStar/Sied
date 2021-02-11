<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buzon extends Model
{
  protected $table = "buzon";

  public function usuario()
  {
    return $this->hasOne("App\User", "id", "usuario_id");
  }
}
