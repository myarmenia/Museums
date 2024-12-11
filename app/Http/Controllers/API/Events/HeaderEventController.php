<?php

namespace App\Http\Controllers\API\Events;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Events\EventListResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HeaderEventController extends BaseController
{
    /**
     * Handle the incoming request.
     */

    public function index(Request $request)
    {

      $event=Event::latest()->where(['status' => 1, 'online_sales' => 1])->whereDate('end_date', '>=', Carbon::today())->take(6)->get();

      return $this->sendResponse(EventListResource::collection($event),'success');

    }
}
