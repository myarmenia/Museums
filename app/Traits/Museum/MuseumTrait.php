<?php
namespace App\Traits\Museum;
use App\Models\Event;
use App\Models\Museum;
use Carbon\Carbon;

trait MuseumTrait
{
  public function getAllMuseums($request)
  {


      if (request()->type == 'event' || request()->type == 'event-config') {

          $request['status'] = 1;
      }

      $data = Museum::filter($request->all())->has('standart_tickets')->get();
      return $data;

  }

  public function getMuseumEvents($request)
  {
    $request['status'] = 1;
    $request['online_sales'] = 1;

    $events = Event::filter($request->all())->whereDate('end_date', '>=', Carbon::today())->get();

    return $events;

  }

}
