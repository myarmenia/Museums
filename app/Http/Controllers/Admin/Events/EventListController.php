<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventListController extends Controller
{
  public function __invoke(Request $request){
    $data = Event::where('id','>',0)
    // return view('content.event.index',compact("data"));
    ->orderBy('id', 'DESC')->paginate(2)->withQueryString();
    return view('content.event.index', [
        'data' => $data,

    ])
         ->with('i', ($request->input('page', 1) - 1) * 2);
  }

}
