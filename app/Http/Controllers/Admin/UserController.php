<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Users\UserCrudTrait;


class UserController extends Controller
{
    use UserCrudTrait;
    function __construct()
    {
        $this->middleware('role:super_admin|museum_admin');
        $this->middleware('user_managment_middleware');
    }


  public function model()
  {
    return User::class;
  }


}
