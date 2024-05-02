<?php
namespace App\Traits\Event;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use stdClass;

trait EventNotificationTrait{
  public function sendEvent($id) {
    $event = Event::where('id',$id)->first();

    $visitors = User::whereHas('roles', function ($query) {
      $query->where('name', 'visitor')->where('status',1);
  })->get();

  $event_obj = new stdClass();
  $event_obj->id = $id;
  $event_obj->lang['am']['name'] =  $event->translation('am')->name;
  $event_obj->lang['ru']['name'] = $event->translation('ru')->name;
  $event_obj->lang['en']['name'] = $event->translation('en')->name;
  $event_obj->start_date = $event->start_date;
  $event_obj->end_date = $event->end_date;
  $event_obj->link = route('singleEvent',$id);


      $event->notify(new EventNotification($event->id, $event_obj));


  }


}
