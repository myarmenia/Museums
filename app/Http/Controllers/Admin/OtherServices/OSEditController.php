<?php

namespace App\Http\Controllers\Admin\OtherServices;

use App\Http\Controllers\Controller;
use App\Traits\Museum\OtherServices;
use Illuminate\Http\Request;

class OSEditController extends Controller
{
  use OtherServices;
  public function __invoke($id)
  {

    $other_service = $this->getOtherService($id);

    if (!$other_service) {
      return redirect()->back();
    }
    return view("content.other-services.edit", compact('other_service'));
  }
}
