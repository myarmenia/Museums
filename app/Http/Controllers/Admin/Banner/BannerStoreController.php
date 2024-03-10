<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Models\Banner;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class BannerStoreController extends Controller
{
  use StoreTrait;
    /**
     * Handle the incoming request.
     */
    protected  $model;
    public function model()
    {
      return Banner::class;
    }
    public function store(BannerRequest $request)
    {
      // dd($request->all());
      $banner = $this->itemStore($request);

      if($banner){

        return redirect()->route('banner_list');
      }
    }
}
