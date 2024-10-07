<?php

namespace App\Services\Log;

use App\Models\CashierLog;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierService
{
  public static function store($model)
  {

      if ($model->type == 'offline') {
          $create = CashierLog::create([
                'user_id' => Auth::id(),
                'item_relation_id' => $model->id,
                'db_name' => 'purchases',
                'action' => 'store'
              ]);

              return $create ? true : false;
      }

  }


  public static function returned($model)
  {

    if ($model->status == 'returned') {
      $create = CashierLog::create([
        'user_id' => Auth::id(),
        'item_relation_id' => $model->id,
        'db_name' => 'ticket_qrs',
        'action' => 'return'
      ]);

      return $create ? true : false;
    }

  }


  public static function showDetails($id)
  {


      $log = CashierLog::find($id);

      $relation_name = $log->db_name;

      $item = $log->{$relation_name};
        // dd($item);
      return $item ? $item : false;

  }



}
