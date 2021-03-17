<?php

namespace App;

use Carbon\Carbon;
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

  public function valorAntiguedad()
  {
    $creacion = $this->fecha_constitucion;
    $birthDate = Carbon::createFromFormat('Y-m-d', $creacion);
    $currentDate = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
    $diferencia = $currentDate->diffInYears($birthDate);
    $puntaje = [];
    switch ($diferencia) {
      case $diferencia < 2:
        $puntaje = ['etiqueta' => 'MENOR A 2 AÑOS', 'puntaje' => 25];
        break;
      case $diferencia >= 2 && $diferencia <= 4:
        $puntaje = ['etiqueta' => 'DE 2 A 4 AÑOS', 'puntaje' => 15];
        break;
      case $diferencia >= 4 && $diferencia <= 9:
        $puntaje = ['etiqueta' => 'DE 4 A 9 AÑOS', 'puntaje' => 8];
        break;
      case $diferencia >= 10 && $diferencia <= 22:
        $puntaje = ['etiqueta' => 'MAS DE 10 AÑOS menor a 22', 'puntaje' => 2];
        break;
      case $diferencia >= 23 && $diferencia <= 50:
        $puntaje = ['etiqueta' => 'DE 23 A 50 AÑOS', 'puntaje' => 10];
        break;
      case $diferencia >= 51 && $diferencia <= 99:
        $puntaje = ['etiqueta' => 'DE 51 A 99 AÑOS', 'puntaje' => 15];
        break;
    }
    return $puntaje;
  }

  public function actividad_profesion()
  {
    $puntaje = [];
    array_push($puntaje, ['criterio'=>$this->perfil->efrr->descripcion, 'factor' => 'Actividad Economica', 'ponderacion' => 40.00, 'puntaje' => $this->perfil->efrr->puntaje]);
    array_push($puntaje, ['criterio'=>$this->perfil->profesionn->descripcion, 'factor' => 'Giro/ Profesion', 'ponderacion' => 60.00, 'puntaje' => $this->perfil->profesionn->puntaje]);
    return $puntaje;
  }

  public function origen_recursos()
  {
    $puntaje = [];
    array_push($puntaje, ['criterio'=>$this->perfil->origenn->descripcion,'factor'=>'Origen de los Recursos','ponderacion'=>40.00,'puntaje'=>$this->perfil->origenn->puntaje]);
    array_push($puntaje, ['criterio'=>$this->perfil->divisa->descripcion,'factor'=>'Divisas','ponderacion'=>20.00,'puntaje'=>$this->perfil->divisa->puntaje]);
    array_push($puntaje, ['criterio'=>$this->perfil->instrumentoo->descripcion,'factor'=>'Instrumento Monetario','ponderacion'=>40.00,'puntaje'=>$this->perfil->instrumentoo->puntaje]);
    return $puntaje;
  }

  public function destino_recursos()
  {
    $puntaje = [];
    array_push($puntaje, ['criterio'=>$this->perfil->destinoo->descripcion,'factor'=>'Destino Recursos','ponderacion'=>100.00,'puntaje'=>$this->perfil->destinoo->puntaje]);
    return $puntaje;
  }

  public function llenarAntecedentes()
  {
    $antecedentes = [];
    $ant = $this->valorAntiguedad();
    $f1 = ['factor' => 'ALERTAS PLD/FT', 'criterio' => 'no', 'ponderacion' => 20.00, 'puntaje' => 30];
    $f2 = ['factor' => 'Edad/Antigüedad', 'criterio' => $ant['etiqueta'], 'ponderacion' => 10.00, 'puntaje' => $ant['puntaje']];
    $f3 = ['factor' => 'Personalidad Juridica', 'criterio' => 'Persona Moral', 'ponderacion' => 15.00, 'puntaje' => 20];
    $f4 = ['factor' => 'Confirmado Listas PEP Mexicano', 'criterio' => 'No', 'ponderacion' => 30.00, 'puntaje' => 20];
    $f5 = ['factor' => 'Confirmado Listas PEP Extranjero', 'criterio' => 'No', 'ponderacion' => 0.00, 'puntaje' => 0];
    $f6 = ['factor' => 'Nacionalidad', 'criterio' => $this->nacionalidad_antecedente->descripcion, 'ponderacion' => 10.00, 'puntaje' => $this->nacionalidad_antecedente->puntaje];
    $f7 = ['factor' => 'Entidad Federativa Residencia', 'criterio' => $this->perfil->efrr->descripcion, 'ponderacion' => 15.00, 'puntaje' => $this->perfil->efrr->puntaje];
    array_push($antecedentes, $f1);
    array_push($antecedentes, $f2);
    array_push($antecedentes, $f3);
    array_push($antecedentes, $f4);
    array_push($antecedentes, $f5);
    array_push($antecedentes, $f6);
    return $antecedentes;
  }

  public function antiguedades()
  {
    $puntaje = [];
    array_push($puntaje, ['descripcion' => 'MENOR A 2 AÑOS', 'puntaje' => 25]);
    array_push($puntaje, ['descripcion' => 'DE 2 A 4 AÑOS', 'puntaje' => 15]);
    array_push($puntaje, ['descripcion' => 'DE 4 A 9 AÑOS', 'puntaje' => 8]);
    array_push($puntaje, ['descripcion' => 'MAS DE 10 AÑOS menor a 22', 'puntaje' => 2]);
    array_push($puntaje, ['descripcion' => 'DE 23 A 50 AÑOS', 'puntaje' => 10]);
    array_push($puntaje, ['descripcion' => 'DE 51 A 99 AÑOS', 'puntaje' => 15]);
    return $puntaje;
  }
}
