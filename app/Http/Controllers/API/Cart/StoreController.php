<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
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
      $data['items_count'] = $row->count();


        // $data = MuseumsViaTicketsResource::collection($museums);



      return $this->sendResponse($data, 'success');

    // } catch (\Throwable $th) {

    //   return $this->sendError($th->errorInfo, 'error');
    // }


  }
}
