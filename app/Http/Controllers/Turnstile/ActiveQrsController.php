<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Turnstile\ActiveQrsRequest;
use Illuminate\Http\Request;

class ActiveQrsController extends Controller
{
    public function __construct(Request $request)
    {
      $request->headers->set('Accept-Language', 'en');

    }

    public function __invoke(ActiveQrsRequest $request)
    {

      // $data = $this->login($request->all());

      // return $data ? $this->sendResponse($data, 'success') : $this->sendError('error');
    }

}
