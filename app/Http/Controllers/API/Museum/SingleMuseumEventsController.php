<?php

namespace App\Http\Controllers\API\Museum;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Events\EventListResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SingleMuseumEventsController extends BaseController
{
  public function __invoke($museum_id)
  {
    $data = Event::where(['museum_id' => $museum_id, 'status' => 1, 'online_sales' => 1])->whereDate('end_date', '>=', Carbon::today())->paginate(12);

    return $this->sendResponse(EventListResource::collection($data), 'success', ['page_count' => $data->lastPage()]);
  }
}
