<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaNegra extends Model
{
    protected $table = 'listas_negras';

    protected $fillable = ['cliente_id', 'name', 'value'];

    public function cliente()
    {
        return $this->belongsTo('App\Client', 'cliente_id', 'id');
    }
    //
}
