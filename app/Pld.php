<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pld extends Model
{
  protected $table = 'pld';

  protected $fillable = ['descripcion', 'puntaje'];
}
