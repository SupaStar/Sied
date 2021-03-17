<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NacionalidadAntecedentes extends Model
{
  protected $table = 'nacionalidad_antecedentes';

  protected $fillable = ['descripcion', 'puntaje'];

  public function morales()
  {
    return $this->hasMany('App\Moral', 'id_nacionalidad_antecedente', 'id');
  }
}
