<?php

namespace App\Http\Controllers\Admin\OtherServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OSCreateController extends Controller
{
  public function __invoke()
  {

      return view("content.other-services.create");
  }
}
