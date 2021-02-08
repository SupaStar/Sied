<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
  protected $table = 'perfil_transacional';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'monto',
    'tcredito',
    'frecuencia',
    'actividad',
    'propietario',
    'proovedor',
    'dactividad',
    'dpasivos',
    'dotro',
    'total',
    'aceptable',
    'difisil',
    'conducta',
    'comentario',
    'cliente_id',
    'origen_recursos',
    'destino_recursos',
    'instrumento_monetario',
    'divisa',
    'profesion',
    'actividad_giro',
    'pld',
    'efr',
    'ingreso'
  ];

  protected $attributes = [
    'actividad' => false,
    'propietario' => false,
    'proovedor' => false,
    'dactividad' => false,
    'dpasivos' => false,
    'dotro' => false,
    'total' => false,
    'aceptable' => false,
    'difisil' => false,
  ];
}
