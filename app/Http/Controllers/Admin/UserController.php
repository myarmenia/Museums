<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\UserCrudTrait;


class UserController extends Controller
{
  use UserCrudTrait;

  public function model()
  {
    return User::class;
  }


}
