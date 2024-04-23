<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Traits\Cart\CartDeleteTrait;
use App\Traits\Cart\CartTrait;
use Illuminate\Http\Request;

class DeleteItemController extends BaseController
{
  use CartTrait, CartDeleteTrait;
  public function __invoke(Request $request)
  {

      $user = auth('api')->user();

      $deleted = $this->deleteItem($user, $request->id);

      $cart = $this->getCartItems($user);
      $data['id'] = $request->id;
      $parrams['items_count'] = $cart->count();

      return $deleted ? $this->sendResponse($data, 'success', $parrams) : $this->sendError('error');

  }
}
