<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialFlujos extends Model
{
    protected $table = 'historial_flujos';

    protected $fillable = [
      'periodo_id',
      'monto',
      'cambio',
      'descripcion'
    ];
}
