<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Cart\CartResource;
use App\Traits\Cart\CartTrait;
use Illuminate\Http\Request;

class ItemsController extends BaseController
{
   use CartTrait;
    public function __invoke(Request $request)
    {

      $user = auth('api')->user();

      $cart = $this->getCartItems($user);
      $products = $this->products($user);
      $tickets = $this->tickets($user);

      $data = new CartResource(['products' => $products, 'tickets' => $tickets]);
      $parrams['items_count'] = $cart->count();

      return $this->sendResponse($data, 'success', $parrams);

    }
}
