<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Antiguedad extends Model
{
    protected $table = 'antiguedad';

    protected $fillable = ['descripcion', 'puntaje'];
}
