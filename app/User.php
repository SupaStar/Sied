<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
  use Notifiable, HasApiTokens;

  protected $table = 'users';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'telefono', 'name', 'lastname', '_lastnameo', 'email', 'password', 'status', 'role', 'activate', 'last_access'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
    'signature',
    'userbranch',
    'remember_code',
    'forgotten_password_code',
    'activation_code',
    'forgotten_password_time',
  ];

  public $timestamps = false;

  public function getFullNameAttribute()
  {
    return "{$this->first_name} {$this->last_name}";
  }

  public function groups()
  {
    return $this->belongsToMany(Group::class, 'users_groups', 'user_id', 'group_id');
  }


  public function is($roleName)
  {
    foreach ($this->groups()->get() as $role) {
      if (strtolower($role->name) == strtolower($roleName)) {
        return true;
      }
    }
    return false;
  }

  public function isIn($aroles = array())
  {
    $userRoles = $this->groups()->pluck('name')->toArray();
    $arr = array_intersect(array_map('strtolower', $aroles), array_map('strtolower', $userRoles));
    return count($arr) > 0;
  }


  public function messages()
  {
    return $this->hasMany(Message::class, 'receiver_id');
  }

  public function buzones()
  {
    return $this->hasMany("App\Buzon", 'usuario_id', "id");
  }
}
