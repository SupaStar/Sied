<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Riesgo extends Model
{
  protected $table = 'riesgo';

  protected $fillable = ['riesgo', 'minimo', 'maximo'];
}
