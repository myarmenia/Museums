<?php

namespace App\Http\Controllers\API\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Tickets\EventsListViaTicketsResource;
use App\Traits\Museum\MuseumTrait;
use Illuminate\Http\Request;

class SingleMuseumEventsTicketsController extends Controller
{
  use MuseumTrait;
  public function __invoke(Request $request)
  {

    try {

        $events = $this->getMuseumEvents($request->id);
      $data = $this->model
        ->filter($request->all())
        ->where('status', 1)
        ->orderBy('id', 'DESC')->paginate(12)->withQueryString();

        return $this->sendResponse(EventsListViaTicketsResource::collection($events), 'success');

    } catch (\Throwable $th) {

        return $this->sendError($th->errorInfo, 'error');
    }


  }
}
