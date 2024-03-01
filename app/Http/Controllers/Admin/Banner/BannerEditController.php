<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerEditController extends Controller
{
  public function edit($id){


    $data = Banner::find($id);




      return view("content.banner.edit", compact("data"));

    



  }
}
