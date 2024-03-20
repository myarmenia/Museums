<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Traits\Cart\CartStoreTrait;
use Illuminate\Http\Request;

class StoreController extends Controller
{
  use CartStoreTrait;
  public function __invoke(Request $request)
  {

    // try {

      $data = $this->cartStore($request->all());



        // $data = MuseumsViaTicketsResource::collection($museums);



      // return $this->sendResponse($data, 'success', $params);

    // } catch (\Throwable $th) {

    //   return $this->sendError($th->errorInfo, 'error');
    // }


  }
}
