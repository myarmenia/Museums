<?php
namespace App\Traits\Museum;
use App\Models\Event;
use App\Models\Museum;

trait MuseumTrait
{
  public function getAllMuseums($request)
  {


      if (request()->type == 'event') {

        $request['status'] = 1;
      }

      $data = Museum::filter($request->all())->has('standart_tickets')->get();
      return $data;

  }

  public function getMuseumEvents($request)
  {
    $request['status'] = 1;
    return Event::filter($request->all())->get();

  }

}
