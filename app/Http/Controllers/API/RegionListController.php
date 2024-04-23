<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Events\RegionResource;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionListController extends Controller
{
    public function __invoke(){
      $regions = Region::all();
      return RegionResource::collection($regions);
    }
}
