<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerListController extends Controller

{
  protected  $model;
    public function index(){

      $data=Banner::all();

      return view('content.banner.index', compact('data'));

    }
}
