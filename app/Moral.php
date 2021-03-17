<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Moral extends Model
{
  protected $table = 'morales';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id_moral',
    'nombre',
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
    'giro',
    'nombre_administrador',
    'fecha_constitucion',
    'garantias',
    'lat',
    'long',
    'numero_empleados',
    'id_nacionalidad_antecedente'
  ];


  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = ['id', 'created_ad', 'updated_ad'];

  //Relationships
  public function personasMorales()
  {
    return $this->hasMany('App\PersonaMoral', 'morales_id', 'id');
  }
  public function perfil()
  {
    return $this->hasOne('App\PerfilMoral', 'id_moral', 'id');
  }
  public function nacionalidad_antecedente()
  {
    return $this->hasOne('App\NacionalidadAntecedentes', 'id', 'id_nacionalidad_antecedente');
  }
}
