<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  protected $table = 'clientes';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'lastname',
    'o_lastname',
    'gender',
    'date_birth',
    'country_birth',
    'nationality',
    'place_birth',
    'job',
    'street',
    'exterior',
    'inside',
    'pc',
    'colony',
    'town',
    'city',
    'ef',
    'country',
    'phone1',
    'phone2',
    'email',
    'curp',
    'rfc',
    'c_name',
    'c_lastname',
    'c_o_lastname',
    'c_phone',
    'c_email',
    'status',
    'grupo_id',
    'consulta_id',
    'suma_id',
    'suma_estado'
  ];

  protected $appends = ['direccion', 'nombreCompleto'];

  public function getNombreCompletoAttribute()
  {
    return $this->name . ' ' . $this->lastname . ' ' . $this->o_lastname;
  }

  public function getDireccionAttribute()
  {
    return $this->street . ' #' . $this->exterior . ' Colonia ' . $this->colony;
  }

  public function listasNegras()
  {
    return $this->hasMany('App\ListaNegra', 'cliente_id', 'id');
  }

  public function grupo()
  {
    return $this->belongsTo('App\Grupo', 'grupo_id', 'id');
  }

  public function alertas()
  {
    return $this->hasOne('App\Alerta', 'cliente_id', 'id');
  }

  public function riesgo()
  {
    return $this->hasOne("App\Riesgos", "id_cliente", 'id');
  }
}
