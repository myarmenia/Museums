<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class BannerUpdateController extends Controller
{
  use UpdateTrait;
  public function model()
  {
    return Banner::class;
  }
  public function update(BannerRequest $request, string $id){

    $banner = $this->itemUpdate($request,$id);

    if($banner){
      return redirect()->back();

    }
  }
}
