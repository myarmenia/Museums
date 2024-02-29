<?php

namespace App\Services;

use App\Services\Log\LogService;
use Illuminate\Support\Facades\DB;
use Auth;

class ChangeStatusService
{
  public static function change_status($request)
  {

      $status = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
      $data = ['status' => $status];

      $update = DB::table($request->tb_name)
        ->where('id', $request->id)
        ->update([$request->field_name => $status]);

      LogService::store($data, Auth::id(), $request->tb_name, "change_status");

      return $update ? $update : false;

  }
}
