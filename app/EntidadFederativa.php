<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntidadFederativa extends Model
{
    protected $table = 'entidad_federativa';

    protected $fillable = [
      'entity',
      'code'
    ];
}
