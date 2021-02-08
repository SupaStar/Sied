<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class PersonaMoral extends Model
{
    protected $table = 'personas_morales';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'morales_id',
        'name',
        'lastname',
        'o_lastname',
        'gender',
        'date_birth',
        'country_birth',
        'nationality',
        'place_birth',
        'job'
    ];

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setDateBirthAttribute($value)
    {
        //$date = DateTime::createFromFormat('m-d-Y', $value);
        $this->attributes['date_birth'] = date_format(date_create_from_format('m-j-Y', '05-13-1993'), 'Y-m-d');
        //$this->attributes['date_birth'] = $value;
    }

    //Relationships
    public function moral()
    {
        return $this->belongsTo('App\Moral', 'morales_id', 'id');
    }
}
