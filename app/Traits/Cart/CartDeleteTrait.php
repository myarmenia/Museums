<?php
namespace App\Traits\Cart;
use App\Models\Cart;

trait CartDeleteTrait
{
    public function deleteItem($user, $item_id)
    {
        $item = Cart::where(['id' => $item_id, 'user_id' => $user->id])->first();
        return $item ? Cart::find($item_id)->delete() : false;
    }

}
