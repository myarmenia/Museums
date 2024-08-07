<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Turnstile extends Authenticatable
{
    use HasFactory;
    protected $table = 'turnstiles';

    protected $guarded = [];

    public function scopeMuseum($query, $mac)
    {
        return $query->where('mac', $mac);
    }

    public function museum()
    {
      return $this->belongsTo(Museum::class, 'museum_id');
    }
    // protected $hidden = ['password'];

    // public function getJWTIdentifier()
    // {
    //   return $this->getKey();
    // }

    // /**
    //  * Return a key value array, containing any custom claims to be added to the JWT.
    //  *
    //  * @return array
    //  */
    // public function getJWTCustomClaims()
    // {
    //   return [];
    // }

}
