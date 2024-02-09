<?php
namespace App\Traits\Users;
use App\Models\User;

trait UsersTrait
{
  public function getAllStudents()
  {
    return User::whereHas("roles", function($q){ $q->where("name", "student"); })->get();
  }
}
