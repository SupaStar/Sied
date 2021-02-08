<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrigenRecursos extends Model
{
    protected $table = 'origen_recursos';

    protected $fillable = ['descripcion', 'puntaje'];
}
