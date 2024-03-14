<?php

namespace App\Http\Controllers\API\Tickets;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Museum\MuseumsViaTicketsResource;
use App\Http\Resources\API\Museum\UnitedTicketsResource;
use App\Traits\Museum\MuseumTrait;
use Illuminate\Http\Request;

class TicketsController extends BaseController
{
  use MuseumTrait;
  public function __invoke(Request $request)
  {

    // try {

      $museums = $this->getAllMuseums();

      if($request->type == 'united'){
        $data = UnitedTicketsResource::collection($museums);
        

      }
      else{
          $data = MuseumsViaTicketsResource::collection($museums);

    }


      return $this->sendResponse($data, 'success');

    // } catch (\Throwable $th) {

    //   return $this->sendError($th->errorInfo, 'error');
    // }


  }
}
