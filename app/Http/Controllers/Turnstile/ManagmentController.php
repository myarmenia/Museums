<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Traits\Turnstile\Managment;
use Illuminate\Http\Request;

class ManagmentController extends BaseController
{
  use Managment;
  public function __invoke(Request $request)
  {

    // $result = $this->turnstile($request->all());
    $result = false;
    // return response()->json('response', $result);
    return $this->sendResponse($result, $result ? 'success' : 'error') ;
  }
}
