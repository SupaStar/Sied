<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Edad extends Model
{
    protected $table = 'edad';

    protected $fillable = ['descripcion', 'puntaje'];
}
