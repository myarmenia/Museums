<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DeleteItemService;
use Illuminate\Http\Request;

class DeleteItemController extends Controller
{

  public function index($tb_name, $id)
  {

    $delete = DeleteItemService::delete($tb_name, $id);

    return response()->json(['result' => $delete]);
  }
}
