<?php

namespace App\Http\Controllers\API\Tickets;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Museum\MuseumsViaTicketsResource;
use App\Http\Resources\API\Tickets\UnitedTicketsResource;
use App\Traits\Museum\MuseumTrait;
use Illuminate\Http\Request;

class TicketsController extends BaseController
{
  use MuseumTrait;
  public function __invoke(Request $request)
  {

    // try {

          $museums = $this->getAllMuseums($request);
          $params = null;

          if($request->type == 'united'){
              $data = UnitedTicketsResource::collection($museums);
              $data = json_decode(json_encode($data));

              $params['min_museum_quantity'] = unitedTicketSettings()->min_museum_quantity;
              $params['discount_percent'] = unitedTicketSettings()->percent;
              $params['min_ticket_quantity'] = ticketType('united')->min_quantity;
              $params['max_ticket_quantity'] = ticketType('united')->max_quantity;


          }
          else{
              $data = MuseumsViaTicketsResource::collection($museums);

          }

        return $this->sendResponse($data, 'success', $params);

    // } catch (\Throwable $th) {

    //     return $this->sendError($th->errorInfo, 'error');
    // }


  }
}
