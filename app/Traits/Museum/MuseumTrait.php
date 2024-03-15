<?php
namespace App\Traits\Museum;
use App\Models\Event;
use App\Models\Museum;

trait MuseumTrait
{
  public function getAllMuseums($request)
  {
    $request['start_date'] = '2024-03-10';
    // dd($request->all());
    $data = Museum::
      filter($request->all())

      ->orderBy('id', 'DESC')->paginate(12)->withQueryString();
      dd($data);
    return Museum::all();
  }
  public function getMuseumEvents($id)
  {
    return Event::where(['museum_id' => $id, 'status' => 1])->get();
  }

}
