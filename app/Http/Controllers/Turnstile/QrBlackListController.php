<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Turnstile\QrBlackListRequest;
use App\Traits\Turnstile\QR;
use Illuminate\Http\Request;

class QrBlackListController extends BaseController
{
  use QR;
    public function __construct(Request $request)
    {
      $request->headers->set('Accept-Language', 'en');

    }

    public function __invoke(QrBlackListRequest $request)
    {

      $data = $this->getSingleMuseumQrBlackList($request->mac);

      return $data ? $this->sendResponse($data, 'success') : $this->sendError('error');
    }
}
