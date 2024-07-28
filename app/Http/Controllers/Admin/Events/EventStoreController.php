<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;

use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class EventStoreController extends Controller
{
  use StoreTrait;
  public function model()
  {
    return Event::class;
  }
    public function __invoke(EventRequest $request){

      $event = $this->itemStore($request);

      if($event){
        $event = Event::orderBy('created_at', 'desc')->first();
        $id=$event->id;
          return redirect()->route('event_edit',compact('id'));
      }
    }
}
