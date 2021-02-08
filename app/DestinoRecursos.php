<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinoRecursos extends Model
{
    protected $table = 'destino_recursos';

    protected $fillable = ['descripcion', 'puntaje'];
}
