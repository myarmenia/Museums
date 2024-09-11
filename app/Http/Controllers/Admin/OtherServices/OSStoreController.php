<?php

namespace App\Http\Controllers\Admin\OtherServices;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtherServices\OSRequest;
use App\Models\OtherService;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class OSStoreController extends Controller
{
  use StoreTrait;

  public function model()
  {
    return OtherService::class;
  }

  public function __invoke(OSRequest $request)
  {

    $other_services = $this->itemStore($request);

    if ($other_services) {

      return redirect()->route('other_services_list');
    }
  }
}
