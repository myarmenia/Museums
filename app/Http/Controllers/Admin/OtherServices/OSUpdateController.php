<?php

namespace App\Http\Controllers\Admin\OtherServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtherServices\OSRequest;
use App\Traits\Museum\OtherServices;
use Illuminate\Http\Request;

class OSUpdateController extends Controller
{
  use OtherServices;

  public function model()
  {
    return OtherServices::class;
  }
  public function __invoke(OSRequest $request, string $id)
  {

    $other_service = $this->itemUpdate($request, $id);

    if ($other_service) {

      return redirect()->back();
    }

  }
}
