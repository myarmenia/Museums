<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChangeStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChangeStatusController extends Controller
{
  public function change_status(Request $request)
  {

    $update = ChangeStatusService::change_status($request);

      return response()->json(['result' => $update]);
  }
}
