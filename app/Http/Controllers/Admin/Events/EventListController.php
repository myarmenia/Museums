<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventListController extends Controller
{
  public function __construct(){

    $this->middleware('role:museum_admin');

  }
  public function __invoke(Request $request){
    $data = Event::where([
                  ['id','>',0],
                  ['museum_id','=',museumAccessId()]
                  ])
    ->orderBy('id', 'DESC')->paginate(10)->withQueryString();
    return view('content.event.index', [
        'data' => $data,

    ])
         ->with('i', ($request->input('page', 1) - 1) * 10);
  }

}
