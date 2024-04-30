<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Turnstile\LoginRequest;
use App\Traits\Turnstile\Authorization;
use Illuminate\Http\Request;

class TurnstileLoginController extends BaseController
{
    use Authorization;
    public function __invoke(LoginRequest $request)
    {

      $data = $this->login($request->all());

      return $data ? $this->sendResponse($data, 'success') : $this->sendError('error');
    }



}
