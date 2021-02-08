<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActividadGiro extends Model
{
    protected $table = 'actividad_giro';

    protected $fillable = ['descripcion', 'puntaje'];
}
