<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Turnstile\CheckQRRequest;
use App\Traits\Turnstile\QR;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckQRController extends BaseController
{
    use QR;

    public function __construct(Request $request)
    {
        $request->headers->set('Accept-Language', 'en');

    }
    public function __invoke(CheckQRRequest $request){



        $check = $this->check($request->all());

        if($check == 'invalid scan'){
            $data['valid'] = false;
            return $this->sendError($check, $data);
        }

        $data = $request->all();
        $data['data-time'] = Carbon::now()->format('d-m-Y H:i:s');
        $data['valid'] = $check;


      return $check ? $this->sendResponse($data, 'QR code is valid') : $this->sendError($data, 'QR code is invalid');

  }
}
