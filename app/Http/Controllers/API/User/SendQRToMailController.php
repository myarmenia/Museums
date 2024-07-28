<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Traits\Users\ListActiveQR;
use App\Traits\Users\SendQRToMail;
use Illuminate\Http\Request;

class SendQRToMailController extends BaseController
{
    use SendQRToMail, ListActiveQR;
    public function __invoke(Request $request)
    {

      $list = $this->sendQR($request->id);

      if($list){
        $data = [
                'success' => true,
                'message' => __('messages.email_success')
        ];
      }
      else{
        $data = [
                'success' => false,
                'message' => __('messages.error')
        ];
      }


      return response()->json($data);

    }
}
