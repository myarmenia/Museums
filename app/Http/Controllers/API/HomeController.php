<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\HomeResource;

class HomeController extends BaseController
{
    public function home(){

        return  $this->sendResponse(new HomeResource(null), 'success');
    }
}
