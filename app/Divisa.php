<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Divisa extends Model
{
    protected $table = 'divisa';

    protected $fillable = ['descripcion', 'puntaje'];
}
