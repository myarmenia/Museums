<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Traits\Turnstile\QR;
use Illuminate\Http\Request;

class CheckQRController extends BaseController
{
    use QR;
    public function __invoke(Request $request){

       $check = $this->check($request->token);

      return $check ? $this->sendResponse('ok', 'success') : $this->sendError('error');

  }
}
