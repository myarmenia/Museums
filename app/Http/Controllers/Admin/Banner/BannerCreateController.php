<?php

namespace App\Http\Controllers\Admin\Banner;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerCreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function create(Request $request)
    {
        $data=Banner::where("id",1)->first();
        if($data==null){

          return view("content.banner.create");
        }else{
          return view("content.banner.create",compact('data'));
        }
    }
}
