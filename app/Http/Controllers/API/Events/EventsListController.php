<?php

namespace App\Http\Controllers\API\Events;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Events\EventListResource;
use App\Http\Resources\API\Events\EventsPageResource;
use App\Http\Resources\API\MuseumListResource;
use App\Models\Event;
use App\Models\Museum;
use App\Models\Region;
use Illuminate\Http\Request;

class EventsListController extends BaseController
{
  protected $model;

	public function __construct(Event $model)
	{
		$this->model = $model;
	}
  public function index(Request $request){

    $data = $this->model
                ->filter($request->all())
      ->where(['status' => 1, 'online_sales' => 1])
      ->orderBy('id', 'DESC')->paginate(12)->withQueryString();
      $museums = Museum::all();
      $museum_list = MuseumListResource::collection($museums);
      return $this->sendResponse(EventListResource::collection($data),'success',['page_count' => $data->lastPage(),'museum_list'=>$museum_list]);


  }
}
