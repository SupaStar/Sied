<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ponderacion extends Model
{
  protected $table = 'ponderacion';

  protected $fillable = ['factor', 'ponderacion','tipo'];
}
