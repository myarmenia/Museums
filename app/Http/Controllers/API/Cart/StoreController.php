<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Cart\CartResource;
use App\Traits\Cart\CartStoreTrait;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
  use CartStoreTrait;
  public function __invoke(Request $request)
  {

    // try {

      $cart_store = $this->cartStore($request->all());


      if(!$cart_store){
          return $this->sendError('error');
      }

      $cart = $this->getCartItems();
      $user = auth('api')->user();
      $data = new CartResource($user);

      $parrams = $cart->count();

      return $this->sendResponse($data, 'success', $parrams);

    // } catch (\Throwable $th) {

    //   return $this->sendError($th->errorInfo, 'error');
    // }


  }
}
