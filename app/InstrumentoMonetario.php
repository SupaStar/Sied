<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstrumentoMonetario extends Model
{
    protected $table = 'instrumento_monetario';

    protected $fillable = ['descripcion', 'puntaje'];
}
