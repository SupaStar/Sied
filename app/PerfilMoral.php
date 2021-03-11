<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilMoral extends Model
{
  protected $table = 'perfil_transacional_moral';
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

  public function moral()
  {
    $this->hasOne('App\Moral', 'id', 'id_moral');
  }

  public function origen()
  {
    $this->hasOne('App\OrigenRecursos', 'id', 'origen_recursos');
  }

  public function destino()
  {
    $this->hasOne('App\DestinoRecursos', 'id', 'destino_recursos');
  }

  public function instrumento()
  {
    $this->hasOne('App\InstrumentoMonetario', 'id', 'instrumento_monetario');
  }

  public function divisa()
  {
    $this->hasOne('App\Divisa', 'id', 'divisas');
  }

  public function profesion()
  {
    $this->hasOne('App\Profesion', 'id', 'profesion');
  }

  public function actividad_giro()
  {
    $this->hasOne('App\ActividadGiro', 'id', 'actividad_giro');
  }

  public function pId()
  {
    $this->hasOne('App\PId', 'id', 'pId');
  }

  public function efr()
  {
    $this->hasOne('App\EFResidencia', 'id', 'efrs');
  }
}
