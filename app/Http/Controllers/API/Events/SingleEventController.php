<?php

namespace App\Http\Controllers\API\Events;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Events\SingleEventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class SingleEventController extends BaseController
{
    public function __invoke($event_id){

      $single_event = Event::find($event_id);
      return $this->sendResponse(new SingleEventResource($single_event),'success');

    }
}
