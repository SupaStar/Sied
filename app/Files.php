<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'client_id',
      'type',
      'path',
      'extension',
      'name',
      'full',
      'user_id'
    ];
}
