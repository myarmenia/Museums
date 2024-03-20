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

      $row = $this->cartStore($request->all());
      // dd($row);
      $cart = [];
      if($row){
        $cart = $this->getCartItems();
        // dd($cart);
      }
      // $data['items_count'] = $row->count();
      $user = auth('api')->user();

      $data = new CartResource($user);

      $parrams['items_count'] = $cart->count();

      return $this->sendResponse($data, 'success', $parrams);

    // } catch (\Throwable $th) {

    //   return $this->sendError($th->errorInfo, 'error');
    // }


  }
}
