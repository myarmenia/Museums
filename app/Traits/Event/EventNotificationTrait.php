<?php
namespace App\Traits\Event;

use App\Models\Event;
use App\Models\User;
use App\Notifications\EventNotification;
use stdClass;

trait EventNotificationTrait{
  public function sendEvent($id) {
    $event = Event::where('id',$id)->first();
    // dd($event->item_translations);
    $visitors = User::whereHas('roles', function ($query) {
      $query->where('name', 'visitor')->where('status',1);
  })->get();

  $event_obj = new stdClass();
  $event_obj->lang['am']['event_text']=  $event->translation('am')->name;
  $event_obj->lang['ru']['event_text']= $event->translation('ru')->name;
  $event_obj->lang['en']['event_text']= $event->translation('en')->name;


    // foreach($visitors as $visitor){
    //   $visitor->notify(new EventNotification($offerData));
    // }


  }


}
