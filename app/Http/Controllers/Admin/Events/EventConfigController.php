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
    public function store(EventConfigRequest $request){
// dd($request->all());
      $id='';
      $config_arr=[];
      foreach($request->event_config as $key=>$value){
        $id=$key;
        $event=Event::find($id);

        foreach($value as $data){


          $data['event_id']=$key;
          $data['visitors_quantity_limitation']=$event->visitors_quantity_limitation;
          $data['price']=$event->price;
          $event_config=EventConfig::create($data);
          array_push($config_arr,$event_config->id);
        }

      }
      $data = $this->getEvent($id);
      $configs= EventConfig::whereIn('id',$config_arr)->get();
      return response()->json(["message"=> $configs]);

    }
    public function update(EventConfigRequest  $request){

      $event_id='';
      foreach($request->event_config as $key=>$value){
        $event_id=$key;
        $event=Event::find($event_id);
        // dd($event);
        foreach($value as $conf_id=>$data){

          $data['event_id']=$key;
          $data['visitors_quantity_limitation']=$event->visitors_quantity_limitation;
          $data['price']=$event->price;

          $event_conf=EventConfig::find($conf_id);
          $event_conf->update($data);





        }

      }

      return response()->json(["message"=>  'updated']);

    }
    public function delete($id){

      $event_configs=EventConfig::where('event_id',$id)->get();
      foreach($event_configs as $item){
        $item->delete;
      }
      return true
    }
}
