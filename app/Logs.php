<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'type',
      'message',
      'controller',
      'function'
    ];
}
