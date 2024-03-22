<?php

namespace App\Http\Controllers\API\Tickets;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Tickets\EventsListViaTicketsResource;
use App\Traits\Museum\MuseumTrait;
use Illuminate\Http\Request;

class SingleMuseumEventsTicketsController extends BaseController
{
  use MuseumTrait;
  public function __invoke(Request $request)
  {

      $events = $this->getMuseumEvents($request->museum_id);

      return $this->sendResponse(EventsListViaTicketsResource::collection($events), 'success');
  }
}
