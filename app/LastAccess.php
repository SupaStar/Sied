<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastAccess extends Model
{
    protected $table = 'users_last_access';
    protected $fillable = [
        'user_id','login'
    ];
}
