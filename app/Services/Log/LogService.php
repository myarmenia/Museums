<?php

namespace App\Services\Log;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogService
{
  public static function store(array|null $data, $tb_id, $tb_name, $type)
  {

      $user = Auth::user();

      $data = $data ? json_encode($data, JSON_UNESCAPED_UNICODE) : null;

      $log = Log::create([
        'user_id' => $user->id,
        'type' => $type,
        'tb_name' => $tb_name,
        'tb_id' => $tb_id,
        'data' => $data

      ]);

      return $log ? true : false;
  }

  public static function all(){
      return Log::latest();
  }



  public static function logFilter(array $data, $model)
  {
      $filteredData = $model->filter($data);

      if (isset($data['role'])) {
        $role = $data['role'];
        $filteredData = $filteredData->whereHas('user', function ($query) use ($role) {
          $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
          });
        });
      }

      return $filteredData;
  }
}
