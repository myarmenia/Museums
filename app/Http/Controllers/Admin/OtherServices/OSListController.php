<?php

namespace App\Http\Controllers\Admin\OtherServices;

use App\Http\Controllers\Controller;
use App\Traits\Museum\OtherServices;
use Illuminate\Http\Request;

class OSListController extends Controller
{
  use OtherServices;
  public function __invoke()
  {

    $data = $this->getAllOtherServices();

    return view("content.other-services.index", compact('data'));
  }
}
