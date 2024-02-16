<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
  use HasFactory, Notifiable, HasRoles, SoftDeletes;

  protected $fillable = [
    'name',
    'surname',
    'email',
    'status',
    'phone',
    'password'

  ];

  protected $hidden = ['password'];



  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  public function roleNames(): array
  {

    return $this->roles->pluck('name')->toArray();
  }
  public function isAdmin()
  {

    foreach ($this->roles()->get() as $role) {
      if ($role->name == "admin" || $role->name == "mentor") {
        return true;
      }
    }

    return false;
  }

}
