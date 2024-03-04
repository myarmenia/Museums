<?php

namespace App\Http\Controllers\API\Banner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerCantroller extends Controller
{
    public function index(){

    $banner=Banner::where('id','>',0)->where('status',1)->with('item_translations')->get();

      return BannerResource::collection($banner);

    }
}
