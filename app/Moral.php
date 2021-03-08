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
    'entrevista',
    'fotografia1',
    'fotografia2',
    'reporte',
    'autorizacion_reporte_circulo_credito',
    'ultima_declaracion_anual',
    'estados_financieros_anuales',
    'estados_financieros_recientes',
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
}
