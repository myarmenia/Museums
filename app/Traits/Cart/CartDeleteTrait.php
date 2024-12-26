<?php
namespace App\Traits\Cart;
use App\Models\Cart;

trait CartDeleteTrait
{
    public function deleteItem($user, $item_id)
    {
        
        return $user->carts()->where('id', $item_id)->delete();
    }

    public function deleteAllItems($user)
    {
        return $user->carts()->delete();
    }

}
