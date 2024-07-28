<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\MuseumListResource;
use App\Models\Museum;
use Illuminate\Http\Request;

class MuseumListController extends Controller
{
    public function __invoke(){

      $museums=Museum::all();
      return MuseumListResource::collection($museums);
    }
}
