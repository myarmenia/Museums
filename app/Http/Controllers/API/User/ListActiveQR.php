<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\User\ListActiveQRResource;
use Illuminate\Http\Request;

class ListActiveQR extends BaseController
{
  use \App\Traits\Users\ListActiveQR;
    public function __invoke()
    {
        $list = $this->getList();
        return $this->sendResponse(ListActiveQRResource::collection($list), 'success');
    }
}
