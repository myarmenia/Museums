<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Turnstile\RegisterRequest;
use App\Traits\Turnstile\Authorization;
use Illuminate\Http\Request;


class TurnstileRegisterController extends BaseController
{
    use Authorization;
    public function __invoke(RegisterRequest $request)
    {

        $data = $this->register($request->all());

        return $data ? $this->sendResponse($data, 'success') : $this->sendError('error');
    }
}
