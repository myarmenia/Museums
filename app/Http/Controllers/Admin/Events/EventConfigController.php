<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventConfigRequest;
use App\Models\Event;
use App\Models\EventConfig;
use App\Traits\Event\EventTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventConfigController extends Controller
{
  use  EventTrait;
    public function __invoke(EventConfigRequest $request){
// dd($request->all());
      $id='';
      $config_arr=[];
      foreach($request->event_config as $key=>$value){
        $id=$key;
        $event=Event::find($id);

        foreach($value as $data){

          if(strtotime($event->start_date)>strtotime($data['day']) || strtotime($event->end_date)<strtotime($data['day'])){
// dd(777);
            return response()->json(["errorMessage"=> "Մուտքագրեք միջոցառման վավեր օր"],422);
            // return redirect()->back()->with(["errorMessage"=>"Մուտքագրեք միջոցառման վավեր օր"]);
          }

          $data['event_id']=$key;

          $event_config=EventConfig::create($data);
          array_push($config_arr,$event_config->id);
        }

      }


      $data = $this->getEvent($id);
      $configs= EventConfig::whereIn('id',$config_arr)->get();
      // $configs= Event::where('id',11)->get();
      // dd($configs);
      return response()->json(["message"=> $configs]);
      // return view('components.edit-event-config');

      // return redirect()->route('event_edit', $id);
    }
}
