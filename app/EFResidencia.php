<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EFResidencia extends Model
{
  protected $table = 'entidad_federativa_residencia';

  protected $fillable = ['descripcion', 'puntaje'];
}
