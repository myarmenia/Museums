<?php
namespace App\Traits\Cart;

trait CartTrait
{
  public function products($user)
  {
      return $user->carts()->where('product_id', '!=', null)->get();
  }

  public function tickets($user)
  {
      return $user->carts()->where('product_id', null)->get();
  }

  public function getCartItems($user)
  {
      return $user->carts();
  }

}
