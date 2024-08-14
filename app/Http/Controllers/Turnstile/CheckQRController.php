<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Turnstile\CheckQRRequest;
use App\Traits\Turnstile\QR;
use App\Traits\Turnstile\Settings;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckQRController extends BaseController
{
    use QR, Settings;

    public function __construct(Request $request)
    {
        $request->headers->set('Accept-Language', 'en');

    }
    public function __invoke(CheckQRRequest $request){



        $check = $this->check($request->all());
        $this->updateLocalIp($request->mac, $request->local_ip);

        // $data = $request->all();
        $data['online'] = $request->online;
        $data['data-time'] = Carbon::now()->timestamp;
        $data['valid'] = true;

        if($check === 'invalid mac'){
            $data['valid'] = false;
            return $this->sendError($check, $data);
        }

        if($check === 'invalid scan'){
            $data['valid'] = false;
            return $this->sendError($check, $data);
        }

        if ($check === 'process finished') {
          unset($data['valid']);
          return  $this->sendResponse($data, $check);
        }


      return $check ? $this->sendResponse($data, 'QR code is valid') : $this->sendError('QR code is invalid', $data);

  }
}
