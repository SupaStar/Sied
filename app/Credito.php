<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table = 'creditos';

    protected $fillable = ['id', 'cliente_id', 'grupo_id', 'nombre', 'monto', 'tipo', 'pagos', 'numero_plazo', 'unidad_plazo', 'status'];

    public function grupo()
    {
        return $this->belongsTo('App\Grupo', 'grupo_id', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'cliente_id', 'id');
    }
  public function alertas()
  {
    return $this->hasOne('App\Alerta', 'credito_id','id');
  }

    //
}
