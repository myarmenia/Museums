<?php
namespace App\Traits\Users;
use App\Models\User;

trait GetUserTrait
{
    public function getUser(int $id)
    {
        return User::find($id);
    }
}
