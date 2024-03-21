<?php
namespace App\Traits\Cart;
use App\Models\Cart;




trait CartDeleteTrait
{
  public function deleteItem($id)
  {
    return Cart::find($id)->delete();
  }

}
