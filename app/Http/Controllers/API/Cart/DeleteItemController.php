<?php

namespace App\Http\Controllers\API\Cart;

use App\Http\Controllers\Controller;
use App\Traits\Cart\CartDeleteTrait;
use Illuminate\Http\Request;

class DeleteItemController extends Controller
{
  use CartDeleteTrait;
  public function __invoke(Request $request)
  {

    try {


        $deleted = $this->deleteItem($request->id);
        // return $this->sendResponse($data, 'success', $parrams);

    } catch (\Throwable $th) {

        return $this->sendError($th->errorInfo, 'error');
    }


  }
}
