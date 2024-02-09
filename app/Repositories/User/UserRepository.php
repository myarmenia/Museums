<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserInterface;
use App\Models\User;

class UserRepository implements UserInterface
{

  public function getUser(int $id)
  {
    return User::find($id);
  }
}
