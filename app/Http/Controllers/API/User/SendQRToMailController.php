<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Traits\Users\ListActiveQR;
use App\Traits\Users\SendQRToMail;
use Illuminate\Http\Request;

class SendQRToMailController extends Controller
{
    use SendQRToMail, ListActiveQR;
    public function __invoke(Request $request)
    {
      $list = $this->sendQR($request->id);
      return true;
    }
}
