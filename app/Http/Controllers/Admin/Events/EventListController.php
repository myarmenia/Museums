<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventListController extends Controller
{
  public function __invoke(){
    $data = Event::all();
    return view('content.event.index',compact("data"));
  }

}
