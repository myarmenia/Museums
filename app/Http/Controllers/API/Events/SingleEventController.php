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

      $single_event = Event::where(['id' => $event_id, 'status' => 1, 'online_sales' => 1])->first();
      return $this->sendResponse(new SingleEventResource($single_event),'success');

    }
}
