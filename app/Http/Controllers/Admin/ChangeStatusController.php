<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChangeStatusController extends Controller
{
  public function change_status(Request $request)
  {
    $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);;

    $update = DB::table($request->tb_name)
      ->where('id', $request->id)
      ->update([$request->field_name => $status]);

      return response()->json(['result' => $update]);
  }
}
