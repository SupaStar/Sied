<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilMoral extends Model
{
  protected $table = 'perfil_transacional_moral';
  protected $fillable = [
    'id_moral',
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
    'dificil',
    'conducta',
    'comentario',
    'cliente_id',
    'destino_recursos',
    'origen_recursos',
    'instrumento_monetario',
    'divisas',
    'profesion',
    'actividad_giro',
    'pld',
    'efr',
    'ingreso'];
  protected $attributes = [
    'actividad' => false,
    'propietario' => false,
    'proovedor' => false,
    'dactividad' => false,
    'dpasivos' => false,
    'dotro' => false,
    'total' => false,
    'aceptable' => false,
    'dificil' => false,
  ];

  public function morall()
  {
    return $this->hasOne('App\Moral', 'id', 'id_moral');
  }

  public function origenn()
  {
    return $this->hasOne('App\OrigenRecursos', 'id', 'origen_recursos');
  }

  public function destinoo()
  {
    return $this->hasOne('App\DestinoRecursos', 'id', 'destino_recursos');
  }

  public function instrumentoo()
  {
    return $this->hasOne('App\InstrumentoMonetario', 'id', 'instrumento_monetario');
  }

  public function divisa()
  {
    return $this->hasOne('App\Divisa', 'id', 'divisas');
  }

  public function profesionn()
  {
    return $this->hasOne('App\Profesion', 'id', 'profesion');
  }

  public function actividad_giroo()
  {
    return $this->hasOne('App\ActividadGiro', 'id', 'actividad_giro');
  }

  public function pIdd()
  {
    return $this->hasOne('App\PId', 'id', 'pId');
  }

  public function efrr()
  {
    return $this->hasOne('App\EFResidencia', 'id', 'efr');
  }
}
