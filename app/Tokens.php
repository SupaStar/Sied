<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tokens extends Model
{
    protected $table = 'tokens';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'company',
      'type',
      'client',
      'secret',
      'grant_type',
      'audience',
      'token'
    ];
}
