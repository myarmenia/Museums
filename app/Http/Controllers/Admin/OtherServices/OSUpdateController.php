<?php

namespace App\Http\Controllers\Admin\OtherServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtherServices\OSRequest;
use App\Models\OtherService;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class OSUpdateController extends Controller
{
  use UpdateTrait;

  public function model()
  {
    return OtherService::class;
  }
  public function __invoke(OSRequest $request, string $id)
  {

    $request['ticket'] = isset($request->ticket) ? true : 0;
    $other_service = $this->itemUpdate($request, $id);

    if ($other_service) {

      return redirect()->back();
    }

  }
}
