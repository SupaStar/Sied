<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = ['responsable_id', 'nombre'];

    public function clientes()
    {
        return $this->hasMany('App\Client', 'grupo_id', 'id');
    }

    public function creditos()
    {
        return $this->hasMany('App\Credito', 'grupo_id', 'id');
    }

    public function responsable()
    {
        return $this->belongsTo('App\Client', 'responsable_id', 'id');
    }
    //
    //
}
