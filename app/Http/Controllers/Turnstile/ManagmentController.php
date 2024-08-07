<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\Controller;
use App\Traits\Turnstile\Managment;
use Illuminate\Http\Request;

class ManagmentController extends Controller
{
  use Managment;
  public function __invoke(Request $request)
  {
      $result = $this->turnstile($request->all());

      return $result ? $this->sendResponse($result, 'success') : $this->sendError('error');
  }
}
