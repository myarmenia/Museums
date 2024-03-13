<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventConfigComponentController extends Controller
{
    public function __invoke(Request $request){
      $data=Event::where('id',11)->get();


      return view('components.edit-event-config',compact ('data'));
    }
}
