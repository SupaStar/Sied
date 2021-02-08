<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalidadJuridica extends Model
{
  protected $table = 'personalidad_juridica';

  protected $fillable = ['descripcion', 'puntaje'];
}
