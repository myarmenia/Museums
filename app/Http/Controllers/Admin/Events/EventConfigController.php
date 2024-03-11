<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventConfig;
use App\Traits\Event\EventTrait;
use Illuminate\Http\Request;

class EventConfigController extends Controller
{
  use  EventTrait;
    public function __invoke(Request $request){
      // dd($request->event_config);
      $id='';
      foreach($request->event_config as $key=>$value){
        $id=$key;
        $event=Event::find($id);

        foreach($value as $data){
          // dd($event->start_date);
          // dd($data['day']);

          if(strtotime($event->start_date)>strtotime($data['day']) || strtotime($event->end_date)<strtotime($data['day'])){
              return redirect()->back()->with(["errorMessage"=>"Մուտքագրեք միջոցառման վավեր օր"]);
          }

          $data['event_id']=$key;

          $event_config=EventConfig::create($data);
        }

      }
      $data = $this->getEvent($id);
      // $data = Event::all();
      return redirect()->route('event_edit', $id);
    }
}
