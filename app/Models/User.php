<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'status',
        'avatar',
        'passport',
        'phone',
        'lesson_quantity',
        'lessons_quantity_per_month',
        'payment_status',
        'password',
        'ip',
    ];

    protected $hidden = ['password'];

    // protected $casts = [
    //     'password' => 'hashed',
    // ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user_course_menegments(){

      return  $this->hasMany(UserCourseMenegment::class);

    }

    public function isAdmin() {

      foreach ($this->roles()->get() as $role)
      {
          if ($role->name == "admin" || $role->name == "mentor")
          {
              return true;
          }
      }

      return false;
  }

}
